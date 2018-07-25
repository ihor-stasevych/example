<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings;

use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\GeneralInformationUpdateCommandInterface;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\GeneralInformationEmailExistException;
use App\AddHash\AdminPanel\Domain\User\Services\AccountSettings\GeneralInformationUpdateServiceInterface;

class GeneralInformationUpdateService implements GeneralInformationUpdateServiceInterface
{
    private $userRepository;

	public function __construct(UserRepositoryInterface $userRepository)
	{
        $this->userRepository = $userRepository;
	}

    /**
     * @param GeneralInformationUpdateCommandInterface $command
     * @throws GeneralInformationEmailExistException
     */
	public function execute(GeneralInformationUpdateCommandInterface $command)
	{
	    $this->validation($command);

        $user = $command->getUser();

        $user->setEmail($command->getEmail());
        $user->setBackupEmail($command->getBackupEmail());
        $user->setFirstName($command->getFirstName());
        $user->setLastName($command->getLastName());
        $user->setPhoneNumber($command->getPhoneNumber());

        $this->userRepository->update();
	}

    /**
     * @param GeneralInformationUpdateCommandInterface $command
     * @throws GeneralInformationEmailExistException
     */
	private function validation(GeneralInformationUpdateCommandInterface $command)
    {
        $currentEmail = new Email($command->getUser()->getEmail());

        if ($currentEmail != $command->getEmail()) {
            if (null !== $this->userRepository->getByEmail($command->getEmail())) {
                throw new GeneralInformationEmailExistException('This email is already taken');
            }
        }
    }
}