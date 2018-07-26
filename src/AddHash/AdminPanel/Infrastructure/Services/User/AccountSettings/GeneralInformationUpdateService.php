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
     * @return array
     * @throws GeneralInformationEmailExistException
     */
	public function execute(GeneralInformationUpdateCommandInterface $command): array
	{
	    $this->validation($command);

        $user = $command->getUser();
        $email = $command->getEmail();
        $backupEmail = $command->getBackupEmail();
        $firstName = $command->getFirstName();
        $lastName = $command->getLastName();
        $phoneNumber = $command->getPhoneNumber();

        $user->setEmail($email);
        $user->setBackupEmail($backupEmail);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPhoneNumber($phoneNumber);

        $this->userRepository->update();

        return [
            'email'       => $email->getEmail(),
            'backupEmail' => $backupEmail->getEmail(),
            'firstName'   => $firstName,
            'lastName'    => $lastName,
            'phoneNumber' => $phoneNumber->getPhone(),
        ];
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