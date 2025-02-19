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

## Création du CV :

- Choix d'un template
- Renseignement des champs :
  - Informations personnelles =>
    - nom : auto
    - prénom : auto
    - email : auto
    - ville : select
    - téléphone : select
    - date de naissance : select
    - permis : select
    - langues : select
  - Formation =>
    - ajouter un diplôme existante : select
    - creer une nouvelle expérience : select
  - Expérience =>
    - ajouter une expérience existante : select
    - creer une nouvelle expérience : select
  - Compétences =>
    - compétences : select plusieurs propositions
  - Savoir être =>
    - savoir être : select plusieurs propositions
- Génération du CV :
  - L'IA reformule le CV pour qu'il passe les ATS
  - Prompts pour ajouter des informations
  - Création d'un fichier pdf
  - Enregistrement dans la base de données
- Téléchargement du CV

Inscription :
- L'utilisateur rempli les champs obligatoires :
  - email
  - password
  - firstname
  - lastname
  - born/ date de naissance
  - phone/ téléphone
  - postal_code/ code postal
  - linkedin
- L'utilisateur confirme son email via un email de confirmation
- L'utilisateur peut ajouter des informations supplémentaires :
  - image/ photo de profil (nullable)
  - city/ ville (nullable)
  - license/ permis (nullable)
  - portfolio_url (nullable)
  - pois/ centre d'intérêt selon une liste (liste) (nullable)
  - languages/ langues selon une liste (liste) (nullable)
- L'utilisateur peut ajouter des formations :
  - title/ nom
  - organization/ école ou centre de formation 
  - description/ description
  - city/ ville
  - postal_code/ code postal (nullable)
  - country/ pays (nullable)
  - is_graduated/ diplômé ou non
  - level/ niveau (liste)
  - degree/ diplôme (image)
  - date_start/ date de début
  - date_end/ date de fin
- L'utilisateur peut ajouter des expériences :
  - title/ nom
  - organization/ entreprise
  - description/ description
  - city/ ville
  - postal_code/ code postal (nullable)
  - country/ pays (nullable)
  - date_start/ date de début
  - date_end/ date de fin (nullable)
- L'utilisateur peut ajouter des savoir-être :
    - name/ nom
- L'utilisateur peut ajouter des compétences :
    - name/ nom

Création du CV :
- L'utilisateur ajoute les informations de l'offre (Offer) :
  - url/ lien de l'offre (nullable)
  - title/ titre de l'offre
  - content/ contenu de l'offre
- L'utilisateur ajoute les informations dans le CV (Cv) :
  - email/ email de l'utilisateur (on propose celui de l'inscription, il peut le changer sur le cv)
  - title/ titre du cv 
  - introduction/ phrase d'accroche (nullable)
  - date_start/ date de début (nullable) (disponibilité)
  - date_end/ date de fin (nullable) (fin de stage ou de contrat)
- L'utilisateur ajoute les informations de la formation (Formation) déjà enregistrée ou ajoute de nouvelles formations :
  - title/ nom
  - organization/ école ou centre de formation 
  - description/ description
  - city/ ville
  - postal_code/ code postal (nullable)
  - country/ pays (nullable)
  - is_graduated/ diplômé ou non
  - level/ niveau (liste)
  - degree/ diplôme (image)
  - date_start/ date de début
  - date_end/ date de fin
- L'utilisateur ajoute les informations de l'expérience (Experience) déjà enregistrée ou ajoute de nouvelles expériences :
  - title/ nom
  - organization/ entreprise
  - description/ description
  - city/ ville
  - postal_code/ code postal (nullable)
  - country/ pays (nullable)
  - date_start/ date de début
  - date_end/ date de fin (nullable)
- L'utilisateur ajoute les informations des compétences (Skill) déjà enregistrée ou ajoute de nouvelles compétences :
  - name/ nom
- L'utilisateur ajoute les informations des savoir-être (SoftSkill) déjà enregistrée ou ajoute de nouveaux savoir-être :
  - name/ nom
- On envoi toute les informations à l'IA pour générer le CV
  - Analyse les informations
  - Retourne le CV avec les informations réarrangées et compatibles ATS
- L'application récupère les données et les mets en place dans le cv
- L'utilisateur valide ou non les informations traitées par l'IA
- Le Cv est généré 

### 1. **Inscription de l'utilisateur**
L'utilisateur commence par créer un compte en remplissant les champs obligatoires :

- **Champs obligatoires :**
  - **Email** : L'utilisateur saisit un email valide.
  - **Password** : Un mot de passe sécurisé est choisi.
  - **Firstname** : Prénom de l'utilisateur.
  - **Lastname** : Nom de l'utilisateur.
  - **Born (Date de naissance)** : Date de naissance.
  - **Phone (Numéro de téléphone)** : Numéro de téléphone.
  - **Postal_code (Code postal)** : Code postal de l'utilisateur.
  - **Linkedin** : Lien vers le profil LinkedIn de l'utilisateur.

- **Confirmation de l'email :**
  - Un email est envoyé pour confirmer l'adresse email saisie. L'utilisateur doit cliquer sur un lien pour confirmer son inscription.

- **Champs supplémentaires (optionnels) :**
  - **Image (photo de profil)** : L'utilisateur peut télécharger une photo de profil (nullable).
  - **City (Ville)** : L'utilisateur peut ajouter sa ville (nullable).
  - **License (Permis)** : Si applicable, l'utilisateur peut ajouter son permis de conduire (nullable).
  - **Portfolio_url** : L'utilisateur peut ajouter un lien vers son portfolio (nullable).
  - **Pois (Centres d'intérêt)** : Liste d'intérêts que l'utilisateur peut choisir.
  - **Languages (Langues)** : Liste de langues parlées par l'utilisateur et son niveau.

### 2. **Ajout de formations et d'expériences**
L'utilisateur peut enrichir son profil avec des formations et des expériences professionnelles :

- **Formations :**
  - **Title (Nom)** : Nom du diplôme ou de la formation.
  - **Organization (Organisation)** : École ou centre de formation.
  - **Description** : Description de la formation.
  - **City (Ville)** : Ville où la formation a eu lieu.
  - **Postal_code (Code postal)** : Code postal de l'établissement (nullable).
  - **Country (Pays)** : Pays de la formation (nullable).
  - **Is_graduated (Diplômé)** : Statut de diplômé ou non.
  - **Level (Niveau)** : Niveau de la formation (ex. Licence, Master, etc.).
  - **Degree (Diplôme)** : Image du diplôme (si disponible).
  - **Date_start (Date de début)** : Date de début de la formation.
  - **Date_end (Date de fin)** : Date de fin de la formation (nullable).

- **Expériences professionnelles :**
  - **Title (Nom)** : Titre de l'expérience (ex. développeur, chef de projet).
  - **Organization (Entreprise)** : Nom de l'entreprise ou organisation.
  - **Description** : Description du rôle ou des missions.
  - **City (Ville)** : Ville où l'expérience a eu lieu.
  - **Postal_code (Code postal)** : Code postal de l'entreprise (nullable).
  - **Country (Pays)** : Pays de l'entreprise (nullable).
  - **Date_start (Date de début)** : Date de début de l'expérience.
  - **Date_end (Date de fin)** : Date de fin de l'expérience (nullable).

### 3. **Création du CV en fonction de l'offre d'emploi**
L'utilisateur peut créer un CV personnalisé en fonction de l'offre d'emploi à laquelle il souhaite postuler.

- **Ajout des informations de l'offre :**
  - **URL (Lien de l'offre)** : Lien vers l'offre d'emploi (nullable).
  - **Title (Titre de l'offre)** : Nom ou titre de l'offre d'emploi.
  - **Content (Contenu de l'offre)** : Description détaillée de l'offre d'emploi.

- **Ajout des informations du CV :**
  - **Email** : L'utilisateur peut choisir d'utiliser l'email de son inscription ou en saisir un autre.
  - **Title (Titre du CV)** : Titre du CV (ex. "Développeur Full Stack").
  - **Introduction (Phrase d'accroche)** : Introduction du CV, pouvant être vide (nullable).
  - **Date_start (Date de début)** : Date de disponibilité de l'utilisateur pour commencer.
  - **Date_end (Date de fin)** : Date de fin de disponibilité, si applicable (nullable).

### 4. **Ajout des formations et des expériences dans le CV**
L'utilisateur peut lier son profil à ses formations et expériences existantes ou en ajouter de nouvelles.

- **Formations :**
  - L'utilisateur peut choisir parmi les formations déjà enregistrées ou en ajouter de nouvelles.
- **Expériences :**
  - L'utilisateur peut choisir parmi ses expériences professionnelles déjà ajoutées ou en ajouter de nouvelles.

### 5. **Ajout des compétences et des savoir-être dans le CV**
L'utilisateur peut ajouter ses compétences et savoir-être au CV.

- **Compétences (Skills)** : L'utilisateur peut choisir parmi les compétences existantes ou en ajouter de nouvelles.
- **Savoir-être (SoftSkills)** : L'utilisateur peut sélectionner parmi une liste de qualités humaines ou en ajouter de nouvelles.

### 6. **Optimisation du CV avec l'intelligence artificielle**
L'IA analyse toutes les informations fournies par l'utilisateur, y compris les compétences, les formations, les expériences et le contenu de l'offre d'emploi.

- **Analyse :**
  - L'IA analyse les informations pour vérifier la compatibilité avec les exigences de l'offre d'emploi.
  - Elle peut aussi suggérer des ajustements ou des optimisations pour que le CV soit mieux adapté aux critères des systèmes de gestion des candidatures (ATS).

- **Retour de l'IA :**
  - Le CV est réorganisé et réoptimisé pour qu'il soit compatible avec les ATS.
  - L'IA peut également proposer des modifications (par exemple, reformuler des descriptions ou ajouter des mots-clés).

### 7. **Validation du CV par l'utilisateur**
L'utilisateur reçoit le CV généré par l'IA avec les propositions de modifications.

- **Validation :**
  - L'utilisateur peut accepter ou refuser les modifications suggérées par l'IA.
  - Il peut également ajuster certains champs manuellement si nécessaire.

- **Finalisation :**
  - Une fois validé, le CV est généré sous un format prêt à être envoyé ou téléchargé.

### 8. **Envoi du CV**
L'utilisateur peut ensuite télécharger son CV ou l'envoyer directement à une plateforme de recrutement, par email ou toute autre méthode proposée.