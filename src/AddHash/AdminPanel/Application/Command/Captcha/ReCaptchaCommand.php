<?php

namespace App\AddHash\AdminPanel\Application\Command\Captcha;

use App\AddHash\AdminPanel\Domain\Captcha\Commands\ReCaptchaCommandInterface;

class ReCaptchaCommand implements ReCaptchaCommandInterface
{
    private $response;

    private $ip;

    private $userAgent;

    public function __construct($response, $ip, $userAgent)
    {
        $this->response = $response;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}