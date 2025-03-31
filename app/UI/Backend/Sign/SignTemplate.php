<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\User\User;
use App\UI\Template;


final class SignTemplate extends Template
{
	/**
	 * The logged-in user.
	 * This can be either the Nette User or a custom User class from App\Core\User.
	 */
	public \Nette\Security\User|User $user;
}
