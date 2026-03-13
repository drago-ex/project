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
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
		RecursiveIteratorIterator::CHILD_FIRST
	);

	foreach ($iterator as $item) {
		$item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
	}
	rmdir($dir);
}


cleanDragoResources();
echo "✅ Done.\n";
