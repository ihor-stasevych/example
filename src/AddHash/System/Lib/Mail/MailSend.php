<?php

namespace App\AddHash\System\Lib\Mail;

use Twig_Environment;
use App\AddHash\System\Lib\MailSender\MailSenderInterface;

class MailSend implements MailSendInterface
{
    private const SUPPORT_EMAIL = 'support@addhash.com';


    private $mailSender;

    private $templating;

    public function __construct(MailSenderInterface $mailSender, Twig_Environment $templating)
    {
        $this->mailSender = $mailSender;
        $this->templating = $templating;
    }

    /**
     * @param string $template
     * @param array $templateParam
     * @param string $subject
     * @param string $toEmail
     * @param string $fromEmail
     * @throws \Exception
     */
    public function handler(
        string $template,
        array $templateParam,
        string $subject,
        string $toEmail,
        string $fromEmail = self::SUPPORT_EMAIL
    ): void
    {
        try {
            $templateTwig = $this->templating->render($template, $templateParam);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->mailSender->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($templateTwig, 'text/html');

        $this->mailSender->send();
    }
}