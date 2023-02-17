<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {   
        $faker = Factory::create('fr_FR');

        $this->addArticle('Article1',$faker->dateTime(),'0',"5489753215", $manager);
        $this->addArticle('Article3',$faker->dateTime(),'0', "5896211002", $manager);
        $this->addArticle('Article4',$faker->dateTime(),'0', "002158889", $manager);
        $this->addArticle('Article5',$faker->dateTime(),'0', "12558884692", $manager);
        $this->addArticle('Article6',$faker->dateTime(),'0', "15488898720", $manager);
        $this->addArticle('Article7',$faker->dateTime(),'0', "0001125486", $manager);
        $this->addArticle('Article8',$faker->dateTime(),'0', "1549542552852", $manager);
        $this->addArticle('Article9',$faker->dateTime(),'0', "2255284000216", $manager);
        $this->addArticle('Article10',$faker->dateTime(),'0', "26420000315", $manager);
        
        $manager->flush();
    }

    public function addArticle ($name, $date, $state, $sn, ObjectManager $manager) {
        $article = new Article();
        $article->setName($name);
        $article->setCategory($this->getReference("category-".rand(0,8)));
        $article->setDateAdded($date);
        $article->setState($state);
        $article->setSerialNumber($sn);
        $manager->persist($article);
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}

