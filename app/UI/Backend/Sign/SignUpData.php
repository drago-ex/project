<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use Drago\Utils\ExtraArrayHash;


class SignUpData extends ExtraArrayHash
{
	// Constants for database column names
	public const string
		Username = 'username',
		Email = 'email',
		Password = 'password',
		Verify = 'verify';

	/** @var string User's username */
	public string $username;

	/** @var string User's email address */
	public string $email;

	/** @var string User's password */
	public string $password;

	/** @var string Password verification (for matching during registration) */
	public string $verify;

	/** @var string Token for user verification or authentication */
	public string $token;
}
