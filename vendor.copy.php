<?php
declare(strict_types=1);

use Composer\InstalledVersions;

require __DIR__ . '/vendor/autoload.php';

$projectRoot = realpath(__DIR__);
assert($projectRoot !== false);


function message(string $msg, string $icon = ''): void
{
	echo $icon . ' ' . $msg . "\n";
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
	ensureDir(dirname($destination));

	if (file_exists($destination)) {
		message($destination, '⚠️ Skipped (exists):');
		return;
	}

	if (!copy($source, $destination)) {
		throw new RuntimeException("Failed copying $source → $destination");
	}

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
	$composerJsonPath = $packagePath . '/composer.json';
	if (!is_file($composerJsonPath)) return;

	$composer = readComposerJson($composerJsonPath);
	$copies = $composer['extra']['drago-project']['install']['copy'] ?? null;
	if ($copies === null) return;

	$type = $composer['type'] ?? 'library';
	if ($type === 'library' && !$allowLibraryInstall) {
		message($composer['name'], '⏭️ Library install disabled by project:');
		return;
	}

	foreach ($copies as $sourceRelative => $destinationRelative) {
		$source = $packagePath . '/' . $sourceRelative;

		if ($destinationRelative === '') {
			$destination = $projectRoot . '/' . basename($source);
		} else {
			$destination = $projectRoot . '/' . $destinationRelative;
			if (is_dir($destination) && is_file($source)) {
				$destination .= '/' . basename($source);
			}
		}

		if (is_dir($source)) {
			recursiveCopy($source, $destination);
		} elseif (is_file($source)) {
			copyFile($source, $destination);
		} else {
			message($source, '❌ Source not found:');
		}
	}
}


$rootComposer = readComposerJson($projectRoot . '/composer.json');
$allowLibraryInstall = $rootComposer['extra']['drago-project']['allow-library-install'] ?? false;

foreach (InstalledVersions::getInstalledPackages() as $packageName) {
	$packagePath = __DIR__ . '/vendor/' . $packageName;
	installPackageResources($packagePath, $projectRoot, $allowLibraryInstall);
}

message("Done installing project resources 🎉");
