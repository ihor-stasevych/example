<?php

namespace App\AddHash\AdminPanel\Application\DataFixtures;

use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\AddHash\AdminPanel\Domain\User\User;
use Doctrine\Common\Persistence\ObjectManager;
use App\AddHash\System\GlobalContext\ValueObject\Email;
use App\AddHash\System\GlobalContext\ValueObject\Phone;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getData();

        if ($data) {
            foreach ($data as $d) {
                $user = new User($d['id'], $d['user_name'], $d['email'], $d['password'], $d['backupEmail'], $d['roles']);
                $user->setFirstName($d['firstName']);
                $user->setLastName($d['lastName']);
                $user->setPhoneNumber($d['phone']);

                $metadata = $manager->getClassMetadata(User::class);
                $metadata->setIdGenerator(new AssignedGenerator());
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

                $manager->persist($user);
            }

            $manager->flush();
        }
    }

    private function getData(): array
    {
        return [
            [
                'id'          => 1,
                'user_name'   => 'asdfa@asdf.com',
                'email'       => new Email('asdfa@asdf.com'),
                'backupEmail' => new Email('asdfa@asdf.com'),
                'firstName'   => '',
                'lastName'    => '',
                'password'    => '$2y$12$q6m7aPF3fXGBxxdEQ6lbHOPzdypxyYNevsNM6m2aO5ACVIjMyoosG',
                'roles'       => ["ROLE_USER"],
                'phone'       => new Phone(''),
            ],
            [
                'id'          => 2,
                'user_name'   => 'mgtherion@gmail.com',
                'email'       => new Email('mgtherion@gmail.com'),
                'backupEmail' => new Email('mgtherion@gmail.com'),
                'firstName'   => '123',
                'lastName'    => '123',
                'password'    => '$2y$12$1r8TJCh8aoHThqC3v5OkneYWufpxvm63fnOhrwiOuhm7eZHuvrv2a',
                'roles'       => ["ROLE_USER"],
                'phone'       => new Phone('123'),
            ],
            [
                'id'          => 3,
                'user_name'   => 'test@tewst.com',
                'email'       => new Email('test@tewst.com'),
                'backupEmail' => new Email('test@tewst.com'),
                'firstName'   => 'Igor',
                'lastName'    => 'Test',
                'password'    => '$2y$12$gMK3Ct7t10CuzTOr2NUHN.tws3vQI2dldHuVODfKwW.a9.Rd6AkyK',
                'roles'       => ["ROLE_USER"],
                'phone'       => new Phone('930907773'),
            ],
            [
                'id'          => 4,
                'user_name'   => 'max@addhash.com',
                'email'       => new Email('max@addhash.com'),
                'backupEmail' => new Email('max@addhash.com'),
                'firstName'   => '',
                'lastName'    => '',
                'password'    => '$2y$12$haz0C3lmxwerUDsqTeOdKeHwndegFnthJevvx8CHiwbBKshq1Yl9O',
                'roles'       => ["ROLE_USER"],
                'phone'       => new Phone(''),
            ],
            [
                'id'          => 5,
                'user_name'   => 'qwerty@gmail.com',
                'email'       => new Email('qwerty@gmail.com'),
                'backupEmail' => new Email('qwerty@gmail.com'),
                'firstName'   => '',
                'lastName'    => '',
                'password'    => '$2y$12$B977EDheZVSSeTk0i/RQIOrTok0UPLQVVqn9N8w7XBGJNQVU3Vej2',
                'roles'       => ["ROLE_USER"],
                'phone'       => new Phone(''),
            ],
            [
                'id'          => 10,
                'user_name'   => 'kurch1992@gmail.com',
                'email'       => new Email('kurch1992@gmail.com'),
                'backupEmail' => new Email('kurch1992@gmail.com'),
                'firstName'   => 'Viacheslav',
                'lastName'    => 'Kurch',
                'password'    => '$2y$12$B977EDheZVSSeTk0i/RQIOrTok0UPLQVVqn9N8w7XBGJNQVU3Vej2',
                'roles'       => ["ROLE_USER"],
                'phone'       => new Phone('380634287730'),
            ],
        ];
    }
}