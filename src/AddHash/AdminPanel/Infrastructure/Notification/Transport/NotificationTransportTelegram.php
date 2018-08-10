<?php

namespace App\AddHash\AdminPanel\Infrastructure\Notification\Transport;

use App\AddHash\AdminPanel\Domain\Notification\Transport\Model\NotificationTransportInterface;

class NotificationTransportTelegram implements NotificationTransportInterface
{
	const BOT_API_KEY = '559894220:AAF3o3cHsvV1k5FVOI_tuIHg-90Qsbi3fbY';
	const API_URL = 'https://api.telegram.org/bot';

	private $chatId;
	private $ch;
	private $logger;

	public function __construct()
	{
		$this->chatId = -269064976;
		$this->prepareConnection();
	}

	public function prepareConnection()
	{
		$this->ch = curl_init();
	}

	public function getChatId()
	{
		/*
		 * $data = file_get_contents($this->getUpdatesUrl());

		if (!$data) {
			return false;
		}

		var_dump($data);die;

		return $this->chatId = $data['message']['chat']['id'];
		 */

		return $this->chatId;

	}

	public function sendMessage($message)
	{
		$url = $this->getApiUrl() . '/sendMessage?chat_id=' . $this->getChatId() . '&text=' . $message;

		$opt = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true
		];

		curl_setopt_array($this->ch, $opt);

		return curl_exec($this->ch);
	}


	public function getApiKey()
	{
		return self::BOT_API_KEY;
	}

	public function getApiUrl()
	{
		return self::API_URL . self::BOT_API_KEY;
	}

	public function getUpdatesUrl()
	{
		return $this->getApiUrl() . '/getUpdates';
	}

}