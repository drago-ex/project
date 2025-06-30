<?php

declare(strict_types=1);

use App\Core\User\UsersEntity;
use Dibi\Connection;
use Dibi\UniqueConstraintViolationException;
use Nette\Security\Passwords;
use Nette\Utils\Random;

require __DIR__ . '/../vendor/autoload.php';

$bootstrap = new App\Bootstrap;
$container = $bootstrap->createContainer();

if (!isset($argv[3])) {
	echo '
Add new user to database.

Usage: create-user.php <username> <email> <password>
';
	exit(1);
}

[, $username, $email, $password] = $argv;

$model = $container->getByType(Connection::class);
\assert($model instanceof Connection);

$hash = $container->getByType(Passwords::class);
\assert($hash instanceof Passwords);

try {
	$model->insert(UsersEntity::Table, [
		UsersEntity::ColumnUsername => $username,
		UsersEntity::ColumnEmail => $email,
		UsersEntity::ColumnPassword => $hash->hash($password),
		UsersEntity::ColumnToken => Random::generate(32),
	])->execute();

	echo "✅ User '$username' was created successfully.\n";

} catch (UniqueConstraintViolationException) {
	echo "❌ Error: duplicate email.\n";
	exit(1);
}
