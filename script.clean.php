<?php

declare(strict_types=1);


function cleanDragoResources(): void
{
	$projectRoot = __DIR__;
	$vendorDir = $projectRoot . '/vendor/drago-ex';

	if (!is_dir($vendorDir)) {
		echo "❌ Vendor drago-ex folder does not exist.\n";
		return;
	}

	foreach (glob($vendorDir . '/*', GLOB_ONLYDIR) as $packageDir) {
		$resourceDir = $packageDir . '/resources';
		if (is_dir($resourceDir)) {
			deleteDir($resourceDir);
			echo "🗑️ Deleted: $resourceDir\n";
		}
	}
}


function deleteDir(string $dir): void
{
	$files = array_diff(scandir($dir), ['.', '..']);
	foreach ($files as $file) {
		$path = $dir . '/' . $file;
		if (is_dir($path)) {
			deleteDir($path);
		} else {
			unlink($path);
		}
	}
	rmdir($dir);
}

cleanDragoResources();
echo "✅ Done.\n";
