<?php

namespace App\Tests\Unit\Services\User\AccountSettings;

use PHPUnit\Framework\TestCase;
use App\AddHash\AdminPanel\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;
use App\AddHash\AdminPanel\Domain\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Application\Command\User\AccountSettings\GeneralInformationUpdateCommand;
use App\AddHash\AdminPanel\Domain\User\Exceptions\AccountSettings\GeneralInformationEmailExistException;
use App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings\GeneralInformationUpdateService;

class GeneralInformationUpdateServiceTest extends TestCase
{
    /**
     * @var UserRepositoryInterface | MockObject
     */
    private $repositoryMock;

    /**
     * @var TokenStorageInterface | MockObject
     */
    private $tokenStorageMock;

    /**
     * @var GeneralInformationUpdateService
     */
    private $service;

    public function setUp()
    {
        $this->repositoryMock = $this->getMockBuilder(UserRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new GeneralInformationUpdateService(
            $this->repositoryMock,
            $this->tokenStorageMock
        );
    }

    public function executeFailedEmailExistsDataProvider()
    {
        return [
            'case_1' => [
                'user' => [
                    null,
                    'Johnny',
                    new Email('john.smith@gmail.com'),
                    'qwerty123',
                    new Email('john.smith.backup@gmail.com'),
                    'John',
                    'Smith',
                    new Phone('380634287730'),
                    [],
                ],
                'command' => [
                    'john.smith.new@gmail.com',
                    'john.smith.backup.new@gmail.com',
                    'John',
                    'Smith',
                    '455000',
                    0
                ],
            ],
        ];
    }

    /**
     * @dataProvider executeFailedEmailExistsDataProvider
     */
    public function testExecuteFailedEmailExists($userData, $commandData)
    {
        $user = new User(...$userData);

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'sso', $user->getRoles());

        $this->tokenStorageMock
            ->method('getToken')
            ->willReturn($token);

        $command = new GeneralInformationUpdateCommand(...array_values($commandData));

        $this->repositoryMock
            ->method('getByEmail')
            ->with($command->getEmail())
            ->willReturn($token->getUser());

        $this->expectException(GeneralInformationEmailExistException::class);

        $this->service->execute($command);
    }

    public function executeSuccessDataProvider()
    {
        return [
            'case_1' => [
                'user' => [
                    null,
                    'Johnny',
                    new Email('john.smith@gmail.com'),
                    'qwerty123',
                    new Email('john.smith.backup@gmail.com'),
                    'John',
                    'Smith',
                    new Phone('380634287730'),
                    [],
                ],
                'expected' => [
                    'email'               => 'john.smith.new@gmail.com',
                    'backupEmail'         => 'john.smith.backup.new@gmail.com',
                    'firstName'           => 'John',
                    'lastName'            => 'Smith',
                    'phoneNumber'         => '455000',
                ],
            ],
        ];
    }

    /**
     * @dataProvider executeSuccessDataProvider
     */
    public function testExecuteSuccess($userData, $expected)
    {
        $user = new User(...$userData);

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'sso', $user->getRoles());

        $this->tokenStorageMock
            ->method('getToken')
            ->willReturn($token);

        $commandData = $expected;
        $commandData['isMonthlyNewsletter'] = 0;

        $command = new GeneralInformationUpdateCommand(...array_values($commandData));

        $this->repositoryMock
            ->method('getByEmail')
            ->with($command->getEmail())
            ->willReturn(null);

        $this->assertEquals($expected, $this->service->execute($command));
    }

    public function executeEmailEqualsSuccessDataProvider()
    {
        return [
            'case_1' => [
                'user' => [
                    null,
                    'Johnny',
                    new Email('john.smith@gmail.com'),
                    '123',
                    new Email('john.smith.backup@gmail.com'),
                    'John',
                    'Smith',
                    new Phone('123456789'),
                    []
                ],
                'expected' => [
                    'email'       => 'john.smith@gmail.com',
                    'backupEmail' => 'john.smith.backup@gmail.com',
                    'firstName'   => 'John',
                    'lastName'    => 'Smith',
                    'phoneNumber' => '987654321',
                ],
            ]
        ];
    }

    /**
     * @dataProvider executeEmailEqualsSuccessDataProvider
     */
    public function testExecuteEmailEqualsSuccess($userData, $expected)
    {
        $user = new User(...$userData);

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'sso', $user->getRoles());

        $this->tokenStorageMock
            ->method('getToken')
            ->willReturn($token);

        $commandData = $expected;
        $commandData['isMonthlyNewsletter'] = 0;

        $command = new GeneralInformationUpdateCommand(...array_values($commandData));

        $this->assertEquals($expected, $this->service->execute($command));
    }
}