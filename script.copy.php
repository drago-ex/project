<?php

declare(strict_types=1);

$projectRoot = __DIR__;
$filesToCopy = [
	'vendor/drago-ex/application/src/Drago/assets/latte/*.latte'
	=> 'app/Core/Widget/',
];

foreach ($filesToCopy as $source => $destination) {
	$pattern = $projectRoot . '/' . $source;
	$sourcePaths = str_contains($source, '*') ? glob($pattern) : [$pattern];

	if (!$sourcePaths) {
		echo "❌ No files matched: $source\n";
		continue;
	}

	foreach ($sourcePaths as $sourcePath) {
		$destinationPath = str_ends_with($destination, '/')
			? rtrim($projectRoot . '/' . $destination, '/') . '/' . basename($sourcePath)
			: $projectRoot . '/' . $destination;

		$destinationDir = dirname($destinationPath);
		if (!is_dir($destinationDir)) {
			mkdir($destinationDir, 0o777, true);
		}

		if (file_exists($destinationPath)) {
			echo "⚠️ Skipped (already exists): $destinationPath\n";
			continue;
		}

		if (copy($sourcePath, $destinationPath)) {
			echo "✅ Copied: $sourcePath → $destinationPath\n";
		} else {
			echo "❌ Failed to copy: $sourcePath\n";
		}
	}
}

echo "Done.\n";
