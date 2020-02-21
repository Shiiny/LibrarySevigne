<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture implements OrderedFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $listUsers = [
            [
                'email' => 'shiny@gmail.com',
                'password' => '123',
                'username' => 'Shiny',
                'role'  =>  ['ROLE_ADMIN']
            ],
            [
                'email' => 'carine@sfr.fr',
                'password' => '456',
                'username' => 'Carine',
                'role'  =>  ['ROLE_USER']

            ],
            [
                'email' => 'test@test.fr',
                'password' => '789',
                'username' => 'test',
                'role'  =>  ['ROLE_USER']
            ],
        ];

        foreach ($listUsers as $list)
        {
            $user = new User();
            $user
                ->setEmail($list['email'])
                ->setPassword($this->passwordEncoder->encodePassword($user, $list['password']))
                ->setUsername($list['username'])
                ->setRoles($list ['role'])
                ->setIsActive(true)
            ;
            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
