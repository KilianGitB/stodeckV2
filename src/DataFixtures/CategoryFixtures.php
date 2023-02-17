<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $this->addCategory('Imprimante', "0", $manager);
        $this->addCategory('Ordinateur', "1", $manager);
        $this->addCategory('Consommable', "2", $manager);
        $this->addCategory('Scanner', "3", $manager);
        $this->addCategory('Périphérique', "4", $manager);
        $this->addCategory('Câbles', "5", $manager);
        $this->addCategory('Smartphone/Tablette', "6", $manager);
        $this->addCategory('Routeur', "7", $manager);
        $this->addCategory('Switch', "8", $manager);

        $manager->flush();
    }

    public function addCategory ($nom, $reference, ObjectManager $manager,) {
        $category = new Category();
        $category->setName($nom);
        $manager->persist($category);
        $this->addReference("category-$reference", $category);

    }
}
