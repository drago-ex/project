<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use Drago\Localization\Translator;
use Nette\Application\UI\TemplateFactory;
use Nette\Mail\Mailer;
use Nette\Mail\Message;


class SignSender
{
	public string $email;
	public string $token;
	private Translator $translator;


	public function __construct(
		private readonly Mailer $mailer,
		private readonly TemplateFactory $templateFactory,
	) {
	}


	public function setTranslator(Translator $translator): void
	{
		$this->translator = $translator;
	}


	public function sendEmail(): void
	{
		$template = $this->templateFactory->createTemplate();
		$template->setFile(__DIR__ . '/email.latte');
		$template->setTranslator($this->translator);
		$template->token = $this->token;

		$message = new Message();
		$message->setFrom('no-reply@scrs.site')
			->addTo($this->email)
			->setSubject($this->translator->translate('Request to reset password'))
			->setHtmlBody($template->__toString());

		try {
			$this->mailer->send($message);

		} catch (\Throwable $e) {
			Debugger::log($e, Debugger::ERROR);
		}
	}
}
