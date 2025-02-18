<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use App\Entity\SoftSkill;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $categories = [
            "Compétences relationnelles",
            "Gestion et Organisation",
            "Intelligence émotionnelle",
            "Pensée critique et créativité",
            "Apprentissage et adaptabilité",
            "Ethique et professionnalisme"
        ];

        // Créer et persister les catégories
        $createdCategories = [];
        foreach ($categories as $cat) {
            $category = new Category();
            $category->setName($cat);
            $manager->persist($category);
            $createdCategories[] = $category;
        }

        $softSkills1 = [
            "Communication",
            "Écoute active",
            "Empathie",
            "Esprit d’équipe",
            "Diplomatie",
            "Négociation",
            "Leadership",
            "Networking",
            "Gestion des conflits",
            "Collaboration interculturelle"
        ];

        $softSkills2 = [
            "Gestion du temps",
            "Capacité d'adaptation",
            "Prise de décision",
            "Gestion du stress",
            "Planification et organisation",
            "Résolution de problèmes",
            "Délégation",
            "Gestion de projet",
            "Priorisation des tâches",
            "Multitâche"
        ];

        $softSkills3 = [
            "Confiance en soi",
            "Gestion des émotions",
            "Résilience",
            "Patience",
            "Persuasion",
            "Auto-motivation",
            "Empathie",
            "Conscience de soi",
            "Régulation émotionnelle",
            "Optimisme"
        ];

        $softSkills4 = [
            "Esprit d'analyse",
            "Curiosité",
            "Innovation",
            "Capacité à résoudre des problèmes complexes",
            "Prise d'initiative",
            "Pensée latérale",
            "Remise en question",
            "Synthèse d'informations",
            "Créativité",
            "Esprit critique"
        ];

        $softSkills5 = [
            "Apprentissage continu",
            "Agilité cognitive",
            "Ouverture au changement",
            "Polyvalence",
            "Flexibilité mentale",
            "Curiosité intellectuelle",
            "Capacité d'auto-formation",
            "Adaptabilité technologique",
            "Gestion de l'ambiguïté",
            "Réceptivité aux feedbacks"
        ];

        $softSkills6 = [
            "Fiabilité",
            "Sens des responsabilités",
            "Intégrité",
            "Déontologie professionnelle",
            "Respect de la confidentialité",
            "Engagement",
            "Honnêteté",
            "Éthique du travail",
            "Respect des normes et procédures",
            "Conscience professionnelle"
        ];

        $allSoftSkills = [
            $softSkills1,
            $softSkills2,
            $softSkills3,
            $softSkills4,
            $softSkills5,
            $softSkills6
        ];

        // Créer et persister les soft skills pour chaque catégorie
        foreach ($allSoftSkills as $index => $softSkills) {
            foreach ($softSkills as $softSkillName) {
                $softSkill = new SoftSkill();
                $softSkill->setName($softSkillName);
                $softSkill->setCategory($createdCategories[$index]);
                $manager->persist($softSkill);
            }
        }


        $manager->flush();
    }
}
