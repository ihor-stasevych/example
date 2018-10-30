<?php

namespace App\AddHash\System\Lib\Mail;

interface MailSendInterface
{
    public function handler(
        string $template,
        array $templateParam,
        string $subject,
        string $toEmail,
        string $fromEmail
    ): void;
}