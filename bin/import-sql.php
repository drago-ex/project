<?php

declare(strict_types=1);

use Dibi\Connection;

require __DIR__ . '/../vendor/autoload.php';

$bootstrap = new App\Bootstrap();
$container = $bootstrap->createContainer();

$model = $container->getByType(Connection::class);
\assert($model instanceof Connection);

$sqlFile = __DIR__ . '/db.sql';

if (!is_file($sqlFile) || !is_readable($sqlFile)) {
	echo "❌ SQL file '$sqlFile' does not exist or is not readable.\n";
	exit(1);
}

$inTransaction = false;

try {
	$model->begin();
	$inTransaction = true;

	$model->loadFile($sqlFile);

	$model->commit();
	$inTransaction = false;

	echo "✅ Database import completed successfully.\n";

} catch (Throwable $e) {
	if ($inTransaction) {
		$model->rollback();
	}

	echo "❌ Error during database import:\n";
	echo $e->getMessage() . "\n";
	exit(1);
}
