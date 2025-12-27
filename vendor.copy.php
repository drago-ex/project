<?php
declare(strict_types=1);

use Composer\InstalledVersions;

require __DIR__ . '/vendor/autoload.php';

$projectRoot = __DIR__;


function recursiveCopy(string $source, string $destination): void {
	$iterator = new RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

	foreach ($files as $file) {
		/** @var SplFileInfo $file */
		$relativePath = substr($file->getPathname(), strlen($source) + 1);
		$destPath = $destination . '/' . $relativePath;

		if ($file->isDir()) {
			@mkdir($destPath, 0o777, true);
			continue;
		}

		if (file_exists($destPath)) {
			echo "⚠️ Skipped (exists): $destPath\n";
			continue;
		}

		@mkdir(dirname($destPath), 0o777, true);

		if (copy($file->getPathname(), $destPath)) {
			echo "✅ Copied: {$file->getPathname()} → {$destPath}\n";
		} else {
			echo "❌ Failed: {$file->getPathname()} → {$destPath}\n";
		}
	}
}


$packages = InstalledVersions::getInstalledPackages();

foreach ($packages as $packageName) {
	$packagePath = __DIR__ . '/vendor/' . $packageName;
	$composerJsonFile = $packagePath . '/composer.json';
	if (!file_exists($composerJsonFile)) {
		continue;
	}

	$composerData = json_decode(file_get_contents($composerJsonFile), true, 512, JSON_THROW_ON_ERROR);

	if (($composerData['type'] ?? '') !== 'drago-project-resource') {
		continue;
	}

	$extra = $composerData['extra']['drago-project']['install']['copy'] ?? [];
	if (!$extra) {
		continue;
	}

	foreach ($extra as $sourceRelative => $destinationRelative) {
		$source = $packagePath . '/' . $sourceRelative;
		$destination = $projectRoot . '/' . $destinationRelative;

		if (is_dir($source)) {
			recursiveCopy($source, $destination);
		} elseif (is_file($source)) {
			@mkdir(dirname($destination), 0o777, true);
			if (!file_exists($destination)) {
				copy($source, $destination);
				echo "✅ Copied: $source → $destination\n";
			} else {
				echo "⚠️ Skipped (exists): $destination\n";
			}
		} else {
			echo "❌ Source not found: $source\n";
		}
	}
}

echo "🎉 Done installing project resources.\n";
