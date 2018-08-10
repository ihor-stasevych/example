<?php

namespace App\Tests\Unit\Services\User\AccountSettings;

use PHPUnit\Framework\TestCase;
use App\AddHash\AdminPanel\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Infrastructure\Services\User\AccountSettings\GeneralInformationGetService;

class GeneralInformationGetServiceTest extends TestCase
{
    /**
     * @var TokenStorageInterface | MockObject
     */
    private $tokenStorageMock;

    /**
     * @var GeneralInformationGetService
     */
    private $service;

    public function setUp()
    {
        $this->tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new GeneralInformationGetService(
            $this->tokenStorageMock
        );
    }

    public function executeDataProvider()
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
                    'phone'       => '123456789',
                ],
            ]
        ];
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecute($userData, $expected)
    {
        $user = new User(...$userData);

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'sso', $user->getRoles());

        $this->tokenStorageMock
            ->method('getToken')
            ->willReturn($token);

        $this->assertEquals($expected, $this->service->execute());
    }
}