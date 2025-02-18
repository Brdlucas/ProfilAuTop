<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\SoftSkill;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
    ) {
        $this->passwordHasher = $passwordHasher;
    }

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

         // Créer un admin
         $admin = new User();
         $admin->setEmail('admin@admin.com');
         $admin->setRoles(['ROLE_ADMIN']);
         $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
         $admin->setFirstname('Drizzt');
         $admin->setLastname("Do'Urden");
         $admin->setBorn(born: $faker->dateTimeBetween('-60 years', '-18 years'));
         $admin->setPhone($faker->phoneNumber());
         $admin->setPostalCode($faker->postcode());
         $admin->setCity($faker->city());
         $admin->setLanguages([['name' =>'français', 'level' => 'maternel'], ['name' =>'anglais', 'level' => 'C1']]);
         $admin->setPois([['name' => 'Lecture', 'items' => ['Manga', 'Manhwa', 'Light-Novels', 'Romans', 'Fanfictions']], ['name' => 'Sport', 'items' => ['Natation', 'Course à pied', 'Vélo', 'Fitness']], ['name' => 'Jeux mobiles', 'items' => ['Summoners War', 'Marver Futur Fight (top 0%)']]]);
         $admin->setLicences(['B', 'moto']);
         $admin->setLinkedin('https://www.linkedin.com/in/');
         $admin->setPortfolioUrl('https://www.google.com');
         $admin->setIsGpdr(true);
         $admin->setIsTerms(true);
         $admin->setIsMajor(true);
         $admin->setImage('default.png');
         $admin->setIsVerified(true);
         $manager->persist($admin);
 

         // User 1
        $user1 = new User();
        $user1->setEmail('user1@example.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password'));
        $user1->setFirstname($faker->firstName());
        $user1->setLastname($faker->lastName());
        $user1->setBorn($faker->dateTimeBetween('-60 years', '-18 years'));
        $user1->setPhone($faker->phoneNumber());
        $user1->setPostalCode($faker->postcode());
        $user1->setCity($faker->city());
        $user1->setLanguages([
            ['name' => 'espagnol', 'level' => 'B2'],
            ['name' => 'allemand', 'level' => 'A1']
        ]);
        $user1->setPois([
            [
                'name' => 'Musique',
                'items' => ['Guitare', 'Piano', 'Concerts']
            ],
            [
                'name' => 'Voyages',
                'items' => ['Europe', 'Asie']
            ]
        ]);
        $user1->setLicences(['A']);
        $user1->setLinkedin('https://www.linkedin.com/in/' . $faker->userName());
        $user1->setPortfolioUrl($faker->url());
        $user1->setIsGpdr($faker->boolean());
        $user1->setIsTerms(true);
        $user1->setIsMajor(true);
        $user1->setImage('default.png');
        $user1->setIsVerified(false);
        $manager->persist($user1);

        // User 2
        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password'));
        $user2->setFirstname($faker->firstName());
        $user2->setLastname($faker->lastName());
        $user2->setBorn($faker->dateTimeBetween('-60 years', '-18 years'));
        $user2->setPhone($faker->phoneNumber());
        $user2->setPostalCode($faker->postcode());
        $user2->setCity($faker->city());
        $user2->setLanguages([
            ['name' => 'italien', 'level' => 'B1']
        ]);
        $user2->setPois([
            [
                'name' => 'Cuisine',
                'items' => ['Pâtisserie', 'Cuisine italienne']
            ],
            [
                'name' => 'Cinéma',
                'items' => ['Films d\'auteur', 'Comédies']
            ]
        ]);
        $user2->setLicences([]);
        $user2->setLinkedin('https://www.linkedin.com/in/' . $faker->userName());
        $user2->setPortfolioUrl($faker->url());
        $user2->setIsGpdr($faker->boolean());
        $user2->setIsTerms(true);
        $user2->setIsMajor(true);
        $user2->setImage('default.png');
        $user2->setIsVerified(true);
        $manager->persist($user2);

        // User 3
        $user3 = new User();
        $user3->setEmail('user3@example.com');
        $user3->setPassword($this->passwordHasher->hashPassword($user3, 'password'));
        $user3->setFirstname($faker->firstName());
        $user3->setLastname($faker->lastName());
        $user3->setBorn($faker->dateTimeBetween('-60 years', '-18 years'));
        $user3->setPhone($faker->phoneNumber());
        $user3->setPostalCode($faker->postcode());
        $user3->setCity($faker->city());
        $user3->setLanguages([
            ['name' => 'chinois', 'level' => 'A2'],
            ['name' => 'japonais', 'level' => 'A1']
        ]);
        $user3->setPois([
            [
                'name' => 'Arts martiaux',
                'items' => ['Karaté', 'Aïkido']
            ],
            [
                'name' => 'Jeux vidéo',
                'items' => ['MMORPG', 'Jeux de stratégie']
            ]
        ]);
        $user3->setLicences(['B', 'C']);
        $user3->setLinkedin('https://www.linkedin.com/in/' . $faker->userName());
        $user3->setPortfolioUrl($faker->url());
        $user3->setIsGpdr($faker->boolean());
        $user3->setIsTerms(true);
        $user3->setIsMajor(true);
        $user3->setImage('default.png');
        $user3->setIsVerified(true);
        $manager->persist($user3);

        
        $manager->flush();
    }
}
