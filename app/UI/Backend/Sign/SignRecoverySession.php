<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Utils\Random;


/**
 * Session handler for managing password recovery tokens.
 * Handles the creation, validation, and removal of recovery tokens within the session.
 */
readonly class SignRecoverySession
{
	public function __construct(
		private Session $session,
	) {
	}


	private function getSection(): SessionSection
	{
		return $this->session
			->getSection('recovery')
			->setExpiration('30 minutes');
	}


	/**
	 * Sets a new token for password recovery in the session.
	 * Generates a random 6-character token and stores it.
	 */
	public function setToken(): void
	{
		$this->getSection()
			->set('token', Random::generate(6));
	}


	/**
	 * Retrieves the stored password recovery token from the session.
	 */
	public function getToken(): ?string
	{
		return $this->getSection()
			->get('token');
	}


	/**
	 * Marks the token as checked in the session.
	 */
	public function setTokenCheck(): void
	{
		$this->getSection()
			->set('tokenCheck', true);
	}


	/**
	 * Removes the password recovery token and token check from the session.
	 */
	public function removeToken(): void
	{
		$this->getSection()
			->remove(['token', 'tokenCheck']);
	}


	/**
	 * Validates if the provided token matches the stored token in the session.
	 */
	public function isTokenValid(string $token): bool
	{
		return $this->getToken() === $token;
	}


	/**
	 * Creates a SignRecoveryToken object based on the current session data.
	 */
	public function createSignRecoveryToken(): SignRecoveryToken
	{
		return new SignRecoveryToken(
			token: $this->getToken() !== null ? true : null,
			tokenCheck: $this->getSection()->get('tokenCheck') ? true : null,
		);
	}
}
