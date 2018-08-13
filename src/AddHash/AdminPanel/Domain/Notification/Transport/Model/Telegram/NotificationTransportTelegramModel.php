<?php

namespace App\AddHash\AdminPanel\Domain\Notification\Transport\Model;


class NotificationTransportTelegramModel
{
	const BOT_API_KEY = '559894220:AAF3o3cHsvV1k5FVOI_tuIHg-90Qsbi3fbY';
	const API_URL = 'https://api.telegram.org/bot';


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