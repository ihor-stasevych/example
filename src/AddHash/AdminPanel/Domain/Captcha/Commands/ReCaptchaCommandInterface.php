<?php

namespace App\AddHash\AdminPanel\Domain\Captcha\Commands;

interface ReCaptchaCommandInterface
{
    public function getResponse(): string;

    public function getIp(): string;

    public function getUserAgent(): string;
}