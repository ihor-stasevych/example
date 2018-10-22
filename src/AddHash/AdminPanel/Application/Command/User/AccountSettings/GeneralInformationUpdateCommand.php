<?php

namespace App\AddHash\AdminPanel\Application\Command\User\AccountSettings;

use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;
use App\AddHash\AdminPanel\Domain\User\Command\AccountSettings\GeneralInformationUpdateCommandInterface;

final class GeneralInformationUpdateCommand implements GeneralInformationUpdateCommandInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @Assert\Email()
     */
    private $backupEmail;

    /**
     * @var string
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $firstName;

    /**
     * @var string
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your last name must be at least {{ limit }} characters long",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters"
     * )
     */
    private $lastName;

    /**
     * @var string
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "Your phone must be at least {{ limit }} characters long",
     *      maxMessage = "Your phone cannot be longer than {{ limit }} characters"
     * )
     */
    private $phone;

    /**
     * @var bool
     */
    private $isMonthlyNewsletter;

	public function __construct($email, $backupEmail, $firstName, $lastName, $phone, $isMonthlyNewsletter = false)
	{
		$this->email = $email;
		$this->backupEmail = $backupEmail;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->phone = $phone;
		$this->isMonthlyNewsletter = $isMonthlyNewsletter;
	}

	public function getEmail(): Email
	{
		return new Email($this->email);
	}

	public function getBackupEmail(): ?Email
	{
        return (null !== $this->backupEmail)
            ? new Email($this->backupEmail)
            : null;
    }

    public function getFirstName(): string
    {
        return $this->firstName ?? '';
    }

    public function getLastName(): string
    {
        return $this->lastName ?? '';
    }

	public function getPhoneNumber(): ?Phone
	{
		return (null !== $this->phone)
           ? new Phone($this->phone)
           : null;
	}

	public function isMonthlyNewsletter(): bool
    {
        return $this->isMonthlyNewsletter;
    }
}