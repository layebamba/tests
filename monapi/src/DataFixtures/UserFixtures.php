<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        
        $user=new User();
        $user->setUsername("laye");
        $user->setRoles(["ROLE_SUPER_ADMIN"]);
        $password = $this->encoder->encodePassword($user, '1234');
        $user->setPassword($password);
        $user->setNom("diakhite");
        $user->setPrenom("abdoulaye");
        $user->setAdresse("dakar");
        $user->setTelephone("779127661");
        $user->setEmail("diakhite@gmail.com");
        $manager->persist($user);
        $manager->flush();
    }
}
