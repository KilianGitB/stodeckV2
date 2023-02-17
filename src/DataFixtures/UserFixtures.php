<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
      
        for ($i=0; $i < 2; $i++) {

            $this->addUser($faker->firstName(), $faker->password(), $manager);

        }
        
        $manager->flush();
    }

    public function addUser ($username, $passwd, ObjectManager $manager) {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($passwd);
        $user->setRoles([]);
        $manager->persist($user);
    }
}
