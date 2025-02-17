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