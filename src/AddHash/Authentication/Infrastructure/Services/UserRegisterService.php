<?php

namespace App\AddHash\Authentication\Infrastructure\Services;

use App\AddHash\Authentication\Domain\Model\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use App\AddHash\Authentication\Application\Command\UserRegisterCommand;
use App\AddHash\Authentication\Domain\Repository\UserRepositoryInterface;
use App\AddHash\Authentication\Domain\Services\UserRegisterServiceInterface;
use App\AddHash\Authentication\Domain\Exceptions\UserRegister\UserRegisterUserAlreadyExistsException;

final class UserRegisterService implements UserRegisterServiceInterface
{
    private const JWT_MANAGER_ALIAS = 'lexik_jwt_authentication.jwt_manager';


    private $encoderFactory;

    private $container;

    private $userRepository;

    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        ContainerInterface $container,
        UserRepositoryInterface $userRepository
    )
    {
        $this->encoderFactory = $encoderFactory;
        $this->container = $container;
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserRegisterCommand $command
     * @return array
     * @throws UserRegisterUserAlreadyExistsException
     */
    public function execute(UserRegisterCommand $command): array
    {
        $email = $command->getEmail();

        $user = $this->userRepository->getByEmail($email);

        if (null !== $user) {
            throw new UserRegisterUserAlreadyExistsException('User ' . $email . ' already exists');
        }

        $user = new User($email, '', $command->getRoles());

        $encodedPassword = $this->encoderFactory->getEncoder(User::class)->encodePassword(
            $command->getPassword(),
            $user->getSalt()
        );

        $user->setPassword($encodedPassword);

        $this->userRepository->save($user);

        return [
            'id'    => $user->getId(),
            'token' => $this->createToken($user),
        ];
    }

    private function createToken(User $user): string
    {
        $jwtManager = $this->container->get(static::JWT_MANAGER_ALIAS);

        return $jwtManager->create($user);
    }
}