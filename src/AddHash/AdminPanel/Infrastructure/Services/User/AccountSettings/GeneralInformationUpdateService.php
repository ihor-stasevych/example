<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\Authentication\Domain\Model\User;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\GeneralInformationUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\GeneralInformationEmailExistException;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\GeneralInformationUpdateServiceInterface;

class GeneralInformationUpdateService implements GeneralInformationUpdateServiceInterface
{
    private $userRepository;

    private $tokenStorage;

	public function __construct(UserRepositoryInterface $userRepository, TokenStorageInterface $tokenStorage)
	{
        $this->userRepository = $userRepository;
        $this->tokenStorage = $tokenStorage;
	}

    /**
     * @param GeneralInformationUpdateCommandInterface $command
     * @return array
     * @throws GeneralInformationEmailExistException
     */
	public function execute(GeneralInformationUpdateCommandInterface $command): array
	{
		/** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $email = new Email($user->getEmail());

        if (false === $email->equals($command->getEmail())) {

            if (null !== $this->userRepository->getByEmail($command->getEmail())) {
                throw new GeneralInformationEmailExistException('This email is already taken');
            }
        }

        $user->setEmail($command->getEmail());
        $user->setBackupEmail($command->getBackupEmail());
        $user->setFirstName($command->getFirstName());
        $user->setLastName($command->getLastName());
        $user->setPhoneNumber($command->getPhoneNumber());

        $this->userRepository->save($user);

        return [
            'email'       => $command->getEmail()->getEmail(),
            'backupEmail' => $command->getBackupEmail()->getEmail(),
            'firstName'   => $command->getFirstName(),
            'lastName'    => $command->getLastName(),
            'phoneNumber' => $command->getPhoneNumber()->getPhone(),
        ];
	}
}