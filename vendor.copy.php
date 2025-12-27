<?php
declare(strict_types=1);

$projectRoot = __DIR__ . '/..';
$installedFile = $projectRoot . '/vendor/composer/installed.json';

if (!file_exists($installedFile)) {
	echo "❌ installed.json not found. Run composer install first.\n";
	exit(1);
}

$installedPackages = json_decode(file_get_contents($installedFile), true, 512, JSON_THROW_ON_ERROR);


function recursiveCopy(string $source, string $destination): void {
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
		RecursiveIteratorIterator::SELF_FIRST
	);

	foreach ($iterator as $item) {
		$destPath = $destination . '/' . $iterator->getSubPathName();

		if ($item->isDir()) {
			@mkdir($destPath, 0o777, true);
			continue;
		}

		if (file_exists($destPath)) {
			echo "⚠️ Skipped (exists): $destPath\n";
			continue;
		}

		@mkdir(dirname($destPath), 0o777, true);

		if (copy($item->getPathname(), $destPath)) {
			echo "✅ Copied: {$item->getPathname()} → {$destPath}\n";
		} else {
			echo "❌ Failed: {$item->getPathName()} → {$destPath}\n";
		}
	}
}


foreach ($installedPackages['packages'] ?? [] as $package) {
	if (($package['type'] ?? '') !== 'drago-project-resources') {
		continue;
	}

	$extra = $package['extra']['drago-project']['install']['copy'] ?? [];
	if (!$extra) {
		continue;
	}

	foreach ($extra as $sourceRelative => $destinationRelative) {
		$source = $projectRoot . '/vendor/' . $package['name'] . '/' . $sourceRelative;
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
