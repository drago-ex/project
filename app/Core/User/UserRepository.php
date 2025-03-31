<?php

declare(strict_types=1);

namespace App\Core\User;

use Dibi\Connection;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator;
use Nette\Security\IdentityHandler;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;


#[Table(UsersEntity::Table, UsersEntity::ColumnId, class: UsersEntity::class)]
class UserRepository implements Authenticator, IdentityHandler
{
	use Database;

	public function __construct(
		protected Connection $connection,
		private readonly Passwords $password,
	) {
	}


	/**
	 * Authenticates the user using the username and password.
	 *
	 * @throws AuthenticationException If the user is not found or the password is incorrect.
	 * @throws Exception|AttributeDetectionException If there is an error while finding the user.
	 */
	public function authenticate(string $username, string $password): SimpleIdentity
	{
		// Find the user by username.
		$user = $this->findUser($username);

		// User not found.
		if (!$user) {
			throw new AuthenticationException('User not found.', self::IdentityNotFound);
		}

		// Incorrect password.
		if (!$this->password->verify($password, $user->password)) {
			throw new AuthenticationException('Incorrect password.', self::InvalidCredential);
		}

		// If password needs to be rehashed, do it.
		if ($this->password->needsRehash($user->password)) {
			$user->password = $this->password->hash($password);
			$this->save($user);
		}

		// Remove the password from the data before returning identity.
		$user->offsetUnset('password');
		return new SimpleIdentity(id: $user->id, data: $user);
	}


	/**
	 * Saves the user's identity for later use (e.g., for the token).
	 */
	public function sleepIdentity(UserToken|IIdentity $identity): SimpleIdentity
	{
		return new SimpleIdentity($identity->token);
	}


	/**
	 * Loads the user and their role when restoring identity.
	 *
	 * @throws Exception If there is an error while finding the user.
	 * @throws AttributeDetectionException If there is an error while finding attributes.
	 */
	public function wakeupIdentity(IIdentity $identity): ?SimpleIdentity
	{
		// Find the user by ID.
		$user = $this->findUserById($identity->getId());
		if ($user === null) {
			return null;
		}

		return new SimpleIdentity(id: $user->id, data: $user);
	}


	/**
	 * Finds a user by their email.
	 *
	 * @throws Exception If there is an error while finding the user.
	 * @throws AttributeDetectionException If there is an error while finding attributes.
	 */
	private function findUser(string $user): array|UsersEntity|null
	{
		return $this->find(UsersEntity::ColumnEmail, $user)
			->record();
	}


	/**
	 * Finds a user by their ID.
	 *
	 * @throws AttributeDetectionException If there is an error while finding attributes.
	 * @throws Exception If there is an error while finding the user.
	 */
	private function findUserById(string $id): array|UsersEntity|null
	{
		return $this->find(UsersEntity::ColumnToken, $id)
			->record();
	}
}
