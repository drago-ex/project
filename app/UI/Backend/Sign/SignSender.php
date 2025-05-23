<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use Latte\Engine;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Tracy\Debugger;


class SignSender
{
	/** User email. */
	public string $email;

	/** Email subject. */
	public string $subject;

	/** Password recovery token. */
	public string $token;


	public function __construct(
		private readonly Mailer $mailer,
	) {
	}


	public function sendEmail(): void
	{
		$message = new Message();
		$message->setFrom('no-reply@scrs.site')
			->addTo($this->email)
			->setSubject($this->subject)
			->setHtmlBody((new Engine)->renderToString(__DIR__ . '/email.latte', [
				'subject' => $this->subject,
				'token' => $this->token,
			]));

		try {
			$this->mailer->send($message);

		} catch (\Throwable $e) {
			Debugger::log($e, Debugger::ERROR);
		}
	}
}
