<?php

namespace App\AddHash\Authentication\Infrastructure\Services;

use Twig_Environment;
use Symfony\Component\HttpFoundation\RequestStack;
use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\System\Lib\MailSender\MailSenderInterface;
use App\AddHash\Authentication\Domain\Model\UserPasswordRecovery;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use App\AddHash\Authentication\Domain\Repository\UserPasswordRecoveryRepositoryInterface;
use App\AddHash\Authentication\Domain\Command\UserPasswordRecoveryRequestCommandInterface;
use App\AddHash\Authentication\Domain\Services\UserSendResetPasswordEmailServiceInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserResetPassword\UserResetPasswordUserNotExistsException;
use App\AddHash\Authentication\Domain\Exceptions\UserResetPassword\UserResetPasswordManySendsResetException;

final class UserSendResetPasswordEmailService implements UserSendResetPasswordEmailServiceInterface
{
    private const REQUESTED_DURATION = 600;

    private const URL_FRONT_CHANGE_PASSWORD = 'change-password?token_id=';

    private const SUBJECT_MESSAGE_TEXT = 'AddHash Reset User Password';

    const SUPPORT_EMAIL = 'support@addhash.com';


    private $mailSender;

    private $templating;

    private $requestStack;

    private $userRepository;

    private $passwordRecoveryRepository;

    public function __construct(
        MailSenderInterface $mailSender,
        Twig_Environment $templating,
        RequestStack $requestStack,
		UserRepositoryInterface $userRepository,
        UserPasswordRecoveryRepositoryInterface $passwordRecoveryRepository
    )
    {
        $this->mailSender = $mailSender;
        $this->templating = $templating;
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
        $this->passwordRecoveryRepository = $passwordRecoveryRepository;
    }

    /**
     * @param UserPasswordRecoveryRequestCommandInterface $command
     * @throws UserResetPasswordManySendsResetException
     * @throws UserResetPasswordUserNotExistsException
     */
    public function execute(UserPasswordRecoveryRequestCommandInterface $command): void
    {
        $email = $command->getEmail();

        /** @var User $user */
        $user = $this->userRepository->getByEmail($email);

        if (null === $user) {
            throw new UserResetPasswordUserNotExistsException('User with such email does not exists');
        }

        $passwordRecovery = $this->passwordRecoveryRepository->findByUser($user);

        if (null !== $passwordRecovery) {
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($dateTime->getTimestamp() - self::REQUESTED_DURATION);

            if ($passwordRecovery->getRequestedDate() > $dateTime) {
                throw new UserResetPasswordManySendsResetException('Please wait several minutes to try again!');
            }
        }

        if (null === $passwordRecovery) {
            $passwordRecovery = new UserPasswordRecovery($user);
        }

        try {
            $template = $this->templating->render(
                'emails/user-reset-password.html.twig', [
                    'name' => '',
                    'link' => $this->getFrontLink($passwordRecovery->getHash()),
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->mailSender->setSubject(self::SUBJECT_MESSAGE_TEXT)
            ->setFrom(self::SUPPORT_EMAIL)
            ->setTo($user->getEmail())
            ->setBody($template, 'text/html');

        $this->passwordRecoveryRepository->save($passwordRecovery);
        $this->mailSender->send();
    }

    private function getFrontLink(string $hash): string
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $scheme = $currentRequest->getScheme();
        $host = $currentRequest->getHost();

        return $scheme . '://' . $host . '/'. self::URL_FRONT_CHANGE_PASSWORD . $hash;
    }
}