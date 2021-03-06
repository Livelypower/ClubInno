<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('celpynenborg@gmail.com');
        $user->setFirstName('Cel');
        $user->setLastName('Pynenborg');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));
        $user->setApiToken('Ymf0zrZmEvvnlymSBO7p8o61adwkAmB0');

        $manager->persist($user);

        $manager->flush();
    }
}
