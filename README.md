# Profileautop

Projet Hackathon 2025 dédié aux CDA, composition de l'équipe : Nicolas, Mohammad, Lucas.

## Présentation

Profileautop est une application de création de CV basé sur une annonce d'emploi et capable de passer les ATS (Application Tracking System).

- Symfony 7
- MySQL ou MariaDB

## Scénarios

| En tant que | Je veux | Afin de |
| --- | --- | --- |
| Utilisateur | Fournir une annonce d'emploi | de créer un CV |
| Utilisateur | Créer un CV | de l'envoyer à l'employeur |
| Utilisateur | Renseigner des informations | de créer un CV |
| Utilisateur | Télécharger mes CV | de les conserver |

## Attentes

- Application 100% fonctionnelle
- Application responsive
- Présentation de l'application

### Plan de bataille 
- Urls : 
  - / : landing page pour user non connecté
  - /profil : user co : 
    - permettant de se rediriger pour créer des cv, pour voir son historique de cv, ...
    - maj infos user
    - payement abonnement
    - suppr compte
  - /profil/cv : pour voir tous les cv
  - /profil/cv/ref : pour voir, télécharger, modif et suppr un cv
  - /profil/cv/nouveau : pour créer un cv
  - /profil/formation : pour voir toutes les formations
  - /profil/formation/nouveau : pour enregistrer une formation
  - /profil/formation/ref : pour voir, modif, ou suppr une formation
  - /profil/experience : pour voir toutes les experiences
  - /profil/experience/nouveau : pour créer une experience
  - /profil/experience/ref : pour voir, modif, ou suppr une experience
  - /rgpd 
  - /cgu 
  - /mentions-legales 


Chemin : 
Landing page -> inscription -> verification email 
                            -> connexion -> renseignements champs supplémentaires -> page profil -> options

Controllers : 
- UserController
- FormationController
- ExperienceController
- CvController
- OffreController
- PageController
- SecurityController
- RegistrationController

Offres d'abonnement : 
- Offre Gratuite "Essentiel" :
    - Génération de CV basique avec IA
    - 1 modèle de CV compatible ATS
    - Stockage limité à 1 CV
    - Conseils de base pour l'optimisation ATS
  
- Offre "Pro" (9,99€/mois) :
    - Génération de CV avancée avec IA
    - 5 modèles de CV compatibles ATS
    - Stockage illimité de CV
    - Analyse ATS approfondie
    - Suggestions personnalisées pour l'optimisation
    - Support par e-mail

- Offre "Expert" (19,99€/mois) :
    - Toutes les fonctionnalités de l'offre Pro
    - Modèles de CV illimités et personnalisables
    - Analyse ATS en temps réel
    - Intégration avec LinkedIn pour l'importation de profil
    - Suivi des candidatures
    - Support prioritaire