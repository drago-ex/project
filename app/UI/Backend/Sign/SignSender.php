<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\User\User;
use Latte\Engine;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Tracy\Debugger;


readonly class SignSender
{
	public function __construct(
		private Mailer $mailer,
		private User $user,
	) {
	}


	public function createEmail(string $email, string $subject): void
	{
		$message = new Message();
		$message->setFrom('no-reply@example.com')
			->addTo($email)
			->setSubject($subject)
			->setHtmlBody((new Engine)->renderToString(__DIR__ . '/email.latte', [
				'user' => $this->user,
				'subject' => $subject,
			]));

		try {
			$this->mailer->send($message);

		} catch (\Throwable $e) {
			Debugger::log($e, Debugger::ERROR);
		}
	}
}
