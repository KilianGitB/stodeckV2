<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $this->addCustomer('Yves Rocher', $faker->address(), $manager);
        $this->addCustomer('Burger King', $faker->address(), $manager);
        $this->addCustomer('Sony', $faker->address(), $manager);
        $this->addCustomer('Riot', $faker->address(), $manager);
        $this->addCustomer('Philips', $faker->address(), $manager);
        $this->addCustomer('Orange', $faker->address(), $manager);
        $this->addCustomer('Leclerc', $faker->address(), $manager);
        $this->addCustomer('Super U', $faker->address(), $manager);
        $this->addCustomer('Carrefour', $faker->address(), $manager);
        $this->addCustomer('Rituals', $faker->address(), $manager);
        $this->addCustomer('Colgate', $faker->address(), $manager);

        $manager->flush();
    }

    public function addCustomer ($name, $address, ObjectManager $manager) {
        $client = new Customer();
        $client->setName($name);
        $client->setAddress($address);
        $manager->persist($client);
    }
}
