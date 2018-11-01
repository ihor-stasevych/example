<?php

namespace App\AddHash\Authentication\Application\Command\UserData;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GroupSequence;
use App\AddHash\Authentication\Domain\Command\UserData\UserEmailsGetCommandInterface;

/**
 * @GroupSequence({"Type", "UserEmailsGetCommand"})
 */
final class UserEmailsGetCommand implements UserEmailsGetCommandInterface
{
    /**
     * @var array
     * @Assert\Type("array", groups={"Type"})
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "You must specify at least one id"
     * )
     */
    private $usersId;

    public function __construct($usersId)
    {
        $this->usersId = $usersId;
    }

    public function getUsersId(): array
    {
        return $this->usersId;
    }
}