<?php

namespace App\AddHash\Authentication\Infrastructure\Services;

use Twig_Environment;
use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\System\Lib\MailSender\MailSenderInterface;
use App\AddHash\Authentication\Domain\Services\UserSendPasswordUpdateServiceInterface;

final class UserSendPasswordUpdateService implements UserSendPasswordUpdateServiceInterface
{
    private const SUBJECT_MESSAGE_TEXT = 'AddHash Reset User Password';

    const SUPPORT_EMAIL = 'support@addhash.com';


    private $mailSender;

    private $templating;

    public function __construct(MailSenderInterface $mailSender, Twig_Environment $templating)
    {
        $this->mailSender = $mailSender;
        $this->templating = $templating;
    }

    /**
     * @param User $user
     * @param array $userDetails
     * @throws \Exception
     */
    public function execute(User $user, array $userDetails = []): void
    {
        try {
            $template = $this->templating->render(
                'emails/user-reset-password-success.html.twig', [
                    'name' => false === empty($userDetails['name']) ? $userDetails['name'] : '',
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->mailSender->setSubject(self::SUBJECT_MESSAGE_TEXT)
            ->setFrom(self::SUPPORT_EMAIL)
            ->setTo($user->getEmail())
            ->setBody($template, 'text/html');

        $this->mailSender->send();
    }
}