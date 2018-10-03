<?php

namespace App\AddHash\AdminPanel\Domain\Captcha\Services;

use App\AddHash\AdminPanel\Domain\Captcha\Commands\ReCaptchaCommandInterface;

interface ReCaptchaServiceInterface
{
    public function execute(ReCaptchaCommandInterface $command): bool;
}