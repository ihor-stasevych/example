<?php

namespace App\AddHash\Authentication\Application\Command;

use App\AddHash\Authentication\Domain\Model\User;
use Symfony\Component\Validator\Constraints as Assert;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use Symfony\Component\Validator\Constraints\GroupSequence;
use App\AddHash\Authentication\Domain\Command\UserRegisterCommandInterface;

/**
 * @GroupSequence({"Type", "UserRegisterCommand"})
 */
final class UserRegisterCommand implements UserRegisterCommandInterface
{
    /**
     * @var string
     * @Assert\NotBlank(groups={"Type"})
     * @Assert\Type("string", groups={"Type"})
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(groups={"Type"})
     * @Assert\Type("string", groups={"Type"})
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $password;

    /**
     * @var array
     * @Assert\NotBlank(groups={"Type"})
     * @Assert\Type("array", groups={"Type"})
     * @Assert\Expression(
     *     "this.isValidRoles() == true",
     *     message="Roles is not valid"
     * )
     */
    private $roles;

    public function __construct($email, $password, $roles)
    {
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getEmail(): Email
    {
        return new Email($this->email);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function isValidRoles(): bool
    {
        $role = array_unique($this->getRoles());

        if (count($role) != count($this->getRoles())) {
            return false;
        }

        foreach ($this->getRoles() as $role) {
            if (false === in_array($role, User::ROLES)) {
                return false;
            }
        }

        return true;
    }
}