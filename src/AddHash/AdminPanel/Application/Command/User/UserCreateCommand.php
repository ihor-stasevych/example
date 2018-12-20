<?php

namespace App\AddHash\AdminPanel\Application\Command\User;

use App\AddHash\System\GlobalContext\ValueObject\Phone;
use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\AdminPanel\Domain\User\Command\UserCreateCommandInterface;

final class UserCreateCommand implements UserCreateCommandInterface
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $firstName;

	/**
	 * @var string
	 * @Assert\NotBlank()
	 */
	private $lastName;


	/**
	 * @var string
	 */
	private $phone;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $password;

    private $captcha;

    public function __construct(
    	$email, $firstName, $lastName,
	    $password, $phone = null, $captcha = null
    )
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        $this->phone = $phone;
        $this->captcha = $captcha;
    }

    public function getFirstName(): string
    {
    	return $this->firstName;
    }

	public function getLastName(): string
	{
		return $this->firstName;
	}

	public function getPhone(): Phone
	{
		return new Phone($this->phone);
	}

    public function getEmail(): Email
    {
        return new Email($this->email);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCaptcha(): ?string
    {
        return $this->captcha;
    }
}