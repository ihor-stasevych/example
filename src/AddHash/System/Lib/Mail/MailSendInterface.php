<?php

namespace App\AddHash\System\Lib\Mail;

interface MailSendInterface
{
    const SUPPORT_EMAIL = 'support@addhash.com';

    public function handler(
        string $template,
        array $templateParam,
        string $subject,
        string $toEmail,
        string $fromEmail = self::SUPPORT_EMAIL
    ): void;
}