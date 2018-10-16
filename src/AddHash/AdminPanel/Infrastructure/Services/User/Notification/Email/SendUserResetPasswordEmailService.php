<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Notification\Email;

use App\AddHash\AdminPanel\Domain\User\Command\PasswordRecovery\UserPasswordRecoveryRequestCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Password\UserPasswordRecovery;
use App\AddHash\AdminPanel\Domain\User\Password\UserPasswordRecoveryRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Notification\Email\SendUserResetPasswordEmailServiceInterface;
use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\Lib\MailSender\MailSenderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SendUserResetPasswordEmailService implements SendUserResetPasswordEmailServiceInterface
{
	const REQUESTED_DURATION = 600;

	private $mailSender;
	private $templating;
	private $userRepository;
	private $passwordRecoveryRepository;
	private $urlGenerator;

	public function __construct(
		MailSenderInterface $mailSender,
		\Twig_Environment $templating,
		UserRepositoryInterface $userRepository,
		UserPasswordRecoveryRepositoryInterface $passwordRecoveryRepository,
		UrlGeneratorInterface $urlGenerator
	)
	{
		$this->mailSender = $mailSender;
		$this->templating = $templating;
		$this->userRepository = $userRepository;
		$this->passwordRecoveryRepository = $passwordRecoveryRepository;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * @param UserPasswordRecoveryRequestCommandInterface $command
	 * @return bool
	 * @throws \Exception
	 */
	public function execute(UserPasswordRecoveryRequestCommandInterface $command)
	{
		$email = new Email($command->getEmail());

		/** @var User $user */
		$user = $this->userRepository->getByEmail($email);

		if (!$user) {
			throw new \Exception('User with such email does not exists');
		}

		$passwordRecovery = new UserPasswordRecovery($user);

		$dateTime = new \DateTime();
		$dateTime->setTimestamp($dateTime->getTimestamp() - self::REQUESTED_DURATION);

		if ($passwordRecovery->getRequestedDate() < $dateTime) {
			throw new \Exception('Please wait 10 minutes to try again!');
		}

		//TODO::Change this shit
		$link = 'http://dev.addhash.com/change-password?token_id=' . $passwordRecovery->getHash();

		try {
			$template = $this->templating->render(
				'emails/user-reset-password.html.twig',
				['name' => $user->getFirstName(), 'link' => $link]
			);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}


		$this->mailSender->setSubject('AddHash Reset User Password')
			->setFrom('support@addhash.com')
			->setTo($user->getEmail())
			->setBody($template, 'text/html');


		$this->passwordRecoveryRepository->save($passwordRecovery);
		$this->mailSender->send();

		return true;

	}
}