<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;


class SignRecoveryToken
{
	public function __construct(
		public ?string $token = null,
		public ?bool $tokenCheck = null,
	) {
	}
}
