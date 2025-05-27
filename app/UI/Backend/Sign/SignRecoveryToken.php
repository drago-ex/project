<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;


/**
 * This class represents the recovery token and its associated validation flag.
 * It is used to store the state of the recovery token and whether the token has been checked.
 */
class SignRecoveryToken
{
	public function __construct(
		public bool $hasToken = false,
		public bool $isTokenChecked = false,
	) {
	}
}
