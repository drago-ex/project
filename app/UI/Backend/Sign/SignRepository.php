<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Exception\EmailNotFoundException;
use App\Core\User\UsersEntity;
use Dibi\Connection;
use Dibi\Exception;
use Dibi\Result;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;
use Nette\Security\Passwords;


/**
 * Repository for accessing user data in the database, specifically for operations related to user sign-in and recovery.
 * It handles database operations for finding a user by email.
 */
#[Table(UsersEntity::Table, UsersEntity::ColumnId)]
class SignRepository
{
	use Database;

	public function __construct(
		private readonly Connection $connection,
		private readonly Passwords $passwords,
	) {
	}


	/**
	 * Finds a user in the database by their email.
	 * Throws an exception if the user with the given email is not found.
	 *
	 * @throws AttributeDetectionException If there are error detecting attributes.
	 * @throws EmailNotFoundException If no user is found with the provided email.
	 * @throws Exception
	 */
	public function findUserByEmail(string $email): array|UsersEntity|null
	{
		// Attempt to fetch the user based on the provided email.
		$row = $this->find(UsersEntity::ColumnEmail, $email)->execute()
			->setRowClass(UsersEntity::class)
			->fetch();

		// If the user is not found by email.
		if (!$row) {
			throw new EmailNotFoundException('User not found by email.', 1);
		}

		// We will return the found user data.
		return $row;
	}


	/**
	 * Updates the user's password in the data
	 *
	 * @throws Exception If the update fails.
	 */
	public function updatePassword(string $email, string $password): int|null|Result
	{
		return $this->connection->update(UsersEntity::Table, [
			UsersEntity::ColumnPassword => $this->passwords->hash($password),
		])->where(UsersEntity::ColumnEmail, '=?', $email)->execute();
	}
}
