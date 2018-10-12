<?php

namespace App\AddHash\System\Lib\MailSender;


interface MailSenderInterface
{
	/**
	 * @param string $address
	 * @return MailSenderInterface
	 * @internal param string $addresses
	 */
	public function setFrom(string $address): MailSenderInterface;

	/**
	 * @param string|array $addresses
	 * @param null $name
	 * @return MailSenderInterface
	 */
	public function setTo($addresses, $name = null): MailSenderInterface;

	/**
	 * @param $addresses
	 * @param null $name
	 * @return MailSenderInterface
	 */
	public function addTo($addresses, $name = null): MailSenderInterface;

	/**
	 * @param string $body
	 * @param string|null $contentType
	 * @return MailSenderInterface
	 */
	public function setBody(string $body, ?string $contentType = null): MailSenderInterface;

	/**
	 * @param string $subject
	 * @return MailSenderInterface
	 */
	public function setSubject(string $subject): MailSenderInterface;

	/**
	 * @param $data
	 * @param $filename
	 * @param $contentType
	 * @return MailSenderInterface
	 */
	public function addAttachment($data, string $filename, string $contentType): MailSenderInterface;

	/**
	 * @return int
	 */
	public function send(): int;
}