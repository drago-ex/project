<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Exception\EmailNotFoundException;
use App\Core\User\UsersEntity;
use Dibi\Connection;
use Dibi\Row;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;


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
	) {
	}


	/**
	 * Finds a user in the database by their email.
	 * Throws an exception if the user with the given email is not found.
	 *
	 * @throws AttributeDetectionException If there is an error detecting attributes.
	 * @throws EmailNotFoundException If no user is found with the provided email.
	 */
	public function findUserByEmail(string $email): array|Row|null
	{
		// Attempt to fetch the user based on the provided email.
		$user = $this->find(UsersEntity::ColumnEmail, $email)
			->fetch();

		// If no user is found, throw an exception.
		if (!$user) {
			throw new EmailNotFoundException('Email not found.', 1);
		}

		// Return the user data if found.
		return $user;
	}
}
