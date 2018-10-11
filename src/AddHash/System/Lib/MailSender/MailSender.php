<?php

namespace App\AddHash\System\Lib\MailSender;

class MailSender implements MailSenderInterface
{
	/**
	 * @var \Swift_Mailer
	 */
	protected $mailer;

	/**
	 * @var \Swift_Message
	 */
	protected $message;

	public function __construct(\Swift_Mailer $mailer)
	{
		$this->mailer = $mailer;
		$this->message = new \Swift_Message();
	}

	/**
	 * @param string $address
	 * @return MailSenderInterface
	 * @internal param string $addresses
	 */
	public function setFrom(string $address): MailSenderInterface
	{
		$this->message->setFrom($address);
		return $this;
	}

	/**
	 * @param string|array $addresses
	 * @param null $name
	 * @return MailSenderInterface
	 */
	public function setTo($addresses, $name = null): MailSenderInterface
	{
		$this->message->setTo($addresses, $name);
		return $this;
	}

	/**
	 * @param $addresses
	 * @param null $name
	 * @return MailSenderInterface
	 */
	public function addTo($addresses, $name = null): MailSenderInterface
	{
		$this->message->addTo($addresses, $name);
		return $this;
	}

	/**
	 * @param string $body
	 * @param string|null $contentType
	 * @return MailSenderInterface
	 */
	public function setBody(string $body, ?string $contentType = null): MailSenderInterface
	{
		$this->message->setBody($body, $contentType);
		return $this;
	}

	/**
	 * @param string $subject
	 * @return MailSenderInterface
	 */
	public function setSubject(string $subject): MailSenderInterface
	{
		$this->message->setSubject($subject);
		return $this;
	}

	/**
	 * @param $data
	 * @param $filename
	 * @param $contentType
	 * @return MailSenderInterface
	 */
	public function addAttachment($data, string $filename, string $contentType): MailSenderInterface
	{
		// TODO: Implement addAttachment() method.
		return $this;
	}

	/**
	 * @return int
	 */
	public function send(): int
	{
		return $this->mailer->send($this->message);
	}
}