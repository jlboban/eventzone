<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName('Eventzone');
        $user->setLastName('Admin');
        $user->setEmail('admin@eventzone.com');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Admin123$'
        ));
        $user->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
        $manager->persist($user);
        $manager->flush();
    }
}
