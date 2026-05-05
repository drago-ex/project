<?php
declare(strict_types=1);

use Composer\InstalledVersions;

require __DIR__ . '/vendor/autoload.php';

$projectRoot = realpath(__DIR__);
assert($projectRoot !== false);

// set to true for detailed listing.
$verbose = false;
$stats = [
	'copied' => 0,
	'skipped' => 0,
	'errors' => 0,
];


function message(string $msg, string $icon = '', bool $force = false): void
{
	global $verbose;
	if ($verbose || $force) {
		echo $icon . ' ' . $msg . "\n";
	}
}


function ensureDir(string $dir): void
{
	if (is_dir($dir)) {
		return;
	}

	if (!mkdir($dir, 0o777, true) && !is_dir($dir)) {
		throw new RuntimeException("Cannot create directory: $dir");
	}
}


function copyFile(string $source, string $destination): void
{
	global $stats;

	ensureDir(dirname($destination));

	if (file_exists($destination)) {
		message($destination, '⚠️ Skipped (exists):');
		$stats['skipped']++;
		return;
	}

	if (!copy($source, $destination)) {
		$stats['errors']++;
		throw new RuntimeException("Failed copying $source → $destination");
	}

	$stats['copied']++;
	message("$source → $destination", '✅ Copied:');
}


function recursiveCopy(string $source, string $destination): void
{
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
		RecursiveIteratorIterator::SELF_FIRST
	);

	foreach ($iterator as $item) {
		if (!$item instanceof SplFileInfo) continue;

		$targetPath = $destination . '/' . substr($item->getPathname(), strlen($source) + 1);

		if ($item->isDir()) {
			ensureDir($targetPath);
		} else {
			copyFile($item->getPathname(), $targetPath);
		}
	}
}


function readComposerJson(string $path): array
{
	if (!is_file($path)) {
		throw new RuntimeException("composer.json not found: $path");
	}

	return json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
}


function installPackageResources(string $packagePath, string $projectRoot, bool $allowLibraryInstall): void
{
	global $stats, $verbose;

	$composerPath = $packagePath . '/composer.json';
	if (!is_file($composerPath)) {
		return;
	}

	$composer = readComposerJson($composerPath);
	$copies = $composer['extra']['drago-project']['install']['copy'] ?? null;
	if (!$copies) {
		return;
	}

	$type = $composer['type'] ?? 'library';
	if ($type === 'library' && !$allowLibraryInstall) {
		message($composer['name'], '⏭️ Library install disabled:', true);
		return;
	}

	foreach ($copies as $srcRel => $dstRel) {
		$source = $packagePath . '/' . $srcRel;

		if (!file_exists($source)) {
			message($source, '❌ Source not found:');
			$stats['errors']++;
			continue;
		}

		$destination = $dstRel === '' ? $projectRoot . '/' . basename($source) : $projectRoot . '/' . $dstRel;

		if (is_file($source)) {
			if (is_dir($destination) || (!pathinfo($destination, PATHINFO_EXTENSION) && !file_exists($destination))) {
				ensureDir($destination);
				$destination .= '/' . basename($source);
			}

			copyFile($source, $destination);
			continue;
		}

		if (is_dir($source)) {
			recursiveCopy($source, $destination);
			continue;
		}

		$stats['errors']++;
		message($source, '❌ Invalid source:', true);
	}
}


$rootComposer = readComposerJson($projectRoot . '/composer.json');
$allowLibraryInstall = $rootComposer['extra']['drago-project']['allow-library-install'] ?? false;

foreach (InstalledVersions::getInstalledPackages() as $packageName) {
	$packagePath = __DIR__ . '/vendor/' . $packageName;
	installPackageResources($packagePath, $projectRoot, $allowLibraryInstall);
}

echo "✔️ Installed ({$stats['copied']} copied, {$stats['skipped']} skipped, {$stats['errors']} errors)\n";
