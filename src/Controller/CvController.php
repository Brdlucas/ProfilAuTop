<?php

namespace App\Controller;

use App\Service\CvService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test/cv')]
class CvController extends AbstractController
{
    #[Route('/optimize', name: 'app_cv_optimize_test', methods: ['GET'])]
    public function optimizeCv(CvService $cvService): JsonResponse
    {
        // Données de test : plusieurs formations et expériences

        // Formations
        $formations = [
            [
                'title' => 'Master en Informatique',
                'organization' => 'Université de Paris',
                'description' => 'Formation approfondie en développement logiciel et architecture des systèmes.',
                'city' => 'Paris',
                'postal_code' => '75000',
                'country' => 'France',
                'date_start' => '2015-09-01',
                'date_end' => '2017-06-30',
                'is_graduated' => true,
                'level' => 'Master',
            ],
            [
                'title' => 'Licence en Génie Logiciel',
                'organization' => 'Université de Lyon',
                'description' => 'Formation axée sur la programmation, les bases de données et l’algorithmique.',
                'city' => 'Lyon',
                'postal_code' => '69000',
                'country' => 'France',
                'date_start' => '2012-09-01',
                'date_end' => '2015-06-30',
                'is_graduated' => true,
                'level' => 'Licence',
            ],
            [
                'title' => 'Formation DevOps',
                'organization' => 'OpenClassrooms',
                'description' => 'Formation pour apprendre à gérer l’intégration continue et l’automatisation des déploiements.',
                'city' => 'Paris',
                'postal_code' => '75000',
                'country' => 'France',
                'date_start' => '2018-03-01',
                'date_end' => '2018-09-30',
                'is_graduated' => true,
                'level' => 'Certificat',
            ],
        ];

        // Expériences professionnelles
        $experiences = [
            [
                'title' => 'Ingénieur DevOps',
                'organization' => 'Médiane Système',
                'description' => 'Mise en place de pipelines CI/CD sous GitLab, optimisation des processus de déploiement et gestion des environnements de développement.',
                'city' => 'Paris',
                'postal_code' => '75000',
                'country' => 'France',
                'date_start' => '2019-01-01',
                'date_end' => '2022-06-30',
            ],
            [
                'title' => 'Développeur Full Stack',
                'organization' => 'Sopra Steria',
                'description' => 'Développement d’applications web en utilisant des technologies telles que React, Node.js et MongoDB.',
                'city' => 'Lyon',
                'postal_code' => '69000',
                'country' => 'France',
                'date_start' => '2017-03-01',
                'date_end' => '2019-01-01',
            ],
            [
                'title' => 'Stagiaire Développeur Web',
                'organization' => 'WebSolution',
                'description' => 'Assistance dans le développement d’applications web, maintenance et optimisation de sites existants.',
                'city' => 'Marseille',
                'postal_code' => '13000',
                'country' => 'France',
                'date_start' => '2016-06-01',
                'date_end' => '2016-12-31',
            ],
        ];

        // Exemple de compétences à optimiser
        $skills = [
            'skills' => [
                'CI/CD',
                'GitLab',
                'Docker',
                'Kubernetes',
                'Python',
                'C#',
                'Linux',
                'Automatisation des tests',
                'Cloud Computing'
            ]
        ];

        // Exemple de savoir-être (Soft skills) à optimiser
        $softskills = [
            'softskills' => [
                'Travail en équipe',
                'Leadership',
                'Résolution de problèmes',
                'Adaptabilité',
                'Communication efficace'
            ]
        ];

        // Exemple de titre du CV à optimiser
        $title = [
            'title' => 'Ingénieur DevOps avec expertise en CI/CD et Cloud Computing'
        ];

        // Exemple d'introduction à optimiser (facultatif)
        $introduction = [
            'introduction' => 'Ingénieur DevOps passionné par l’automatisation des processus et la gestion des infrastructures. Expérience confirmée en mise en place de pipelines CI/CD, gestion d’environnement cloud et automatisation des tests.'
        ];

        $jobTitle = 'Ingénieur DevOps H/F';
        $jobDescription = '
            Lieu
            Montigny-le-Bretonneux (78)
            &nbsp;
            Avantages
            Extraits de la description complète du poste
            
            Flextime
            Prise en charge du transport quotidien
            RTT
            &nbsp;
            Description du poste
            Dans le cadre d\'un projet pour le compte de l\'un de nos clients nous recherchons un ingénieur DevOps H/F qui sera en charge de mettre en place de nouveaux outils d\'intégration continue. Rattaché(e) à l’équipe logicielle, vous aurez pour mission de déployer et optimiser l’infrastructure DevOps, en garantissant l’automatisation des tests et la fiabilité des livrables. Vous interviendrez également dans le développement d’outils internes et l’accompagnement des équipes de développement.
            
                Vos principales missions
            
            Développer et améliorer les outils internes en C# et Python pour faciliter l’intégration et le test des logiciels.
            Mettre en place et optimiser les pipelines CI/CD sous GitLab et Jenkins (migration depuis SVN).
            Accompagner les développeurs dans la mise en place d’une stratégie de tests automatisés.
                Automatiser et fiabiliser les processus d’intégration et de déploiement.
                Évaluer et intégrer des outils de cybersécurité pour garantir un environnement sécurisé.
                Mettre en place un système de gestion des licences pour optimiser leur suivi et leur utilisation.
                Profil recherchéFormation et expérience
            
            Bac+5 (École d’ingénieurs ou Master) en Informatique, DevOps, ou équivalent.
                3 ans d’expérience minimum en intégration logicielle, DevOps ou automatisation des tests.
                Compétences techniques
            
            Maîtrise des outils CI/CD : GitLab CI/CD, Jenkins (expérience sur SVN appréciée).
            Développement en C# et Python pour la création d’outils internes.
            Bonne connaissance des stratégies d’automatisation des tests.
                Sensibilité aux enjeux de cybersécurité appliquée aux processus DevOps.
                Mieux connaitre Médiane Système :
            
            Filiale du groupe industriel ICE, Médiane Système, est une société d’ingénierie innovante spécialisée en électronique, systèmes embarqués et informatique industrielle. Médiane Système a construit son identité sur une expertise technique de haut niveau en proposant à ses clients, des prestations d’accompagnement au travers de ses départements R&D (DRD) et ConSulting (DCS).
            
                Présente en France (région Parisienne, Rhône-Alpes, Midi-Pyrénées, PACA) et en Belgique, Médiane Système est partenaire de grands comptes industriels et de nombreuses PME. Du secteur automobile au médical, en passant par l’énergie, le ferroviaire, l’aéronautique ou bien les télécoms. Médiane Système intervient exclusivement sur les métiers de l’ingénierie système (logiciel embarqué, logiciel applicatif pour l’industrie, électronique numérique et analogique, banc de tests) dans des environnements technologiques de pointe.
            
                Créée par des ingénieurs, Médiane Système, est une entreprise technique à taille humaine, proche de ses collaborateurs, qui privilégie depuis 30 ans, le développement des compétences et l’épanouissement de ses salariés.
            
                Vous souhaitez acquérir de l’expérience dans des secteurs variés à travers des projets innovants ?
                    Vous recherchez une entreprise à taille humaine qui privilégie un management de proximité ?
            
                    Une hiérarchie qui reste à l’écoute des attentes de ses collaborateurs ?
            
                    Vous recherchez plus qu’un job ? Alors contactez-nous !
            
                Type d\'emploi : Temps plein, CDI
            
            Rémunération : 40 000,00€ à 57 590,00€ par an
            
            Avantages :
            
            Flextime
            Prise en charge du transport quotidien
            RTT
            Horaires :
            
            Du lundi au vendredi
            Travail en journée
            Lieu du poste : En présentiel';


        $resultExperience = $cvService->optimizeCVFieldForATS('experience', $experiences, $jobTitle, $jobDescription);
        $resultFormation = $cvService->optimizeCVFieldForATS('formation', $formations, $jobTitle, $jobDescription);
        $resultSkills = $cvService->optimizeCVFieldForATS('skills', $skills, $jobTitle, $jobDescription);
        $resultSoftSkills = $cvService->optimizeCVFieldForATS('softskills', $softskills, $jobTitle, $jobDescription);
        $resultTitle = $cvService->optimizeCVFieldForATS('title', $title, $jobTitle, $jobDescription);
        $resultIntroduction = $cvService->optimizeCVFieldForATS('introduction', $introduction, $jobTitle, $jobDescription);


        // Retourner la réponse JSON avec les résultats des optimisations pour chaque champ
        return new JsonResponse([
            'experience' => $resultExperience,
            'formation' => $resultFormation,
            'skills' => $resultSkills,
            'softskills' => $resultSoftSkills,
            'title' => $resultTitle,
            'introduction' => $resultIntroduction,
        ]);
    }
}
