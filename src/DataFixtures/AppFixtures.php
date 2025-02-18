<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $categories = [
            "Relationnelle",
            "Gestion et Organisationnelle",
            "Intelligence émotionnelle",
            "Pensée critique et créativité",
            "Apprentissage et adaptabilité",
            "Ethique et valeurs"
        ];

        
        
        $manager->flush();
    }
}
