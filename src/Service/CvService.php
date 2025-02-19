<?php

namespace App\Service;

use OpenAI;

class CvService
{
    private $client;

    public function __construct(string $apiKey)
    {
        $this->client = OpenAI::client($apiKey);
    }

    // Méthode pour analyser un champ du CV et proposer des optimisations pour les ATS
    public function optimizeCVFieldForATS(string $fieldType, array $fieldContent, string $jobTitle, string $jobDescription): array
    {
        set_time_limit(120);

        $prompt = $this->generatePrompt($fieldType, $fieldContent, $jobTitle, $jobDescription);

        $response = $this->client->chat()->create([
            'model' => 'gpt-4',  // Utilisation du modèle GPT-4
            'messages' => [
                ['role' => 'system', 'content' =>
                    'Tu es un assistant IA qui aide à optimiser les CV des utilisateurs pour les ATS. 
                    Donne des suggestions concrètes de remplacement pour chaque champ. 
                    Ne génère que les suggestions, sans explication. 
                    Ne génère que les suggestions en une seule ligne pour chaque champ, sans explication ni détail supplémentaire.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 500,  // Limite du nombre de tokens pour la réponse
        ]);

        // Suggestions d'améliorations pour ce champ
        $suggestions = $response['choices'][0]['message']['content'];

        return [
            'original' => $fieldContent, // Contenu original du champ
            'suggestions' => $suggestions // Suggestions d'améliorations proposées par l'IA
        ];
    }

    // Générer le prompt pour analyser le champ en fonction du type (compétence, expérience, etc.)
    private function generatePrompt(string $fieldType, array $fieldContent, string $jobTitle, string $jobDescription): string
    {
        // Partie générale : l'IA doit toujours être informée du titre et du contenu de l'offre d'emploi
        $generalPrompt =
            "L'offre d'emploi suivante est à optimiser pour les ATS :
            Titre de l'offre d'emploi : $jobTitle
            Description de l'offre d'emploi : $jobDescription
        
            Maintenant, optimise les informations suivantes pour le champ : $fieldType.
            ";

        // Ajout des sections spécifiques selon le type de champ (formation, expérience, etc.)
        switch ($fieldType) {
            case 'experience': // Expériences professionnelles
                $experiencesText = '';
                foreach ($fieldContent as $experience) {
                    $title = $experience['title'] ?? ''; // Gérer la possibilité que 'title' soit manquant
                    $organization = $experience['organization'] ?? '';
                    $description = $experience['description'] ?? '';
                    $city = $experience['city'] ?? '';
                    $postalCode = $experience['postal_code'] ?? '';
                    $country = $experience['country'] ?? '';
                    $dateStart = $experience['date_start'] ?? '';
                    $dateEnd = $experience['date_end'] ?? '';

                    $experiencesText .= "
                    Expérience professionnelle actuelle :
                    - Nom du poste : $title
                    - Organisation : $organization
                    - Description : $description
                    - Ville : $city
                    - Code postal : $postalCode
                    - Pays : $country
                    - Date de début : $dateStart
                    - Date de fin : $dateEnd

                    ";
                }
                return $generalPrompt . $experiencesText . "
                Reformule et réarrange ces expériences si nécessaire, en les rendant plus pertinentes pour l'offre d'emploi et les ATS.
                ";

            case 'title': // Titre du CV
                return $generalPrompt .
                    "Le titre du CV actuel est : {$fieldContent['title']}.
                    Propose un titre optimisé pour ce CV en lien avec l'offre d'emploi.
                    ";

            case 'introduction': // Introduction / Phrase d'accroche
                return $generalPrompt .
                    "Introduction actuelle (facultative) : {$fieldContent['introduction']}.
                    Si l'utilisateur n'a pas fourni d'introduction, propose une phrase d'accroche pertinente en fonction de l'offre d'emploi.
                    ";

            case 'formation': // Formations
                $formationsText = '';
                foreach ($fieldContent as $formation) {
                    $title = $formation['title'] ?? '';
                    $organization = $formation['organization'] ?? '';
                    $description = $formation['description'] ?? '';
                    $city = $formation['city'] ?? '';
                    $postalCode = $formation['postal_code'] ?? '';
                    $country = $formation['country'] ?? '';
                    $isGraduated = $formation['is_graduated'] ? 'Oui' : 'Non';
                    $level = $formation['level'] ?? '';
                    $dateStart = $formation['date_start'] ?? '';
                    $dateEnd = $formation['date_end'] ?? '';

                    $formationsText .= "
                    Formation actuelle :
                    - Nom : $title
                    - Organisation : $organization
                    - Description : $description
                    - Ville : $city
                    - Code postal : $postalCode
                    - Pays : $country
                    - Diplômé : $isGraduated
                    - Niveau : $level
                    - Date de début : $dateStart
                    - Date de fin : $dateEnd

                    ";
                }
                return $generalPrompt . $formationsText . "
                Reformule et réarrange ces formations si nécessaire, en les rendant plus pertinentes pour l'offre d'emploi et les ATS.
                ";

            case 'skills': // Compétences (Skills)
                $skills = implode(", ", $fieldContent['skills']); // Liste des compétences
                return $generalPrompt .
                    "Compétences actuelles : $skills.
                    En fonction de l'offre d'emploi, propose des compétences supplémentaires ou reformule celles existantes pour optimiser le CV pour les ATS.
                    ";

            case 'softskills': // Savoir-être (Soft Skills)
                $softSkills = implode(", ", $fieldContent['softskills']); // Liste des savoir-être
                return $generalPrompt .
                    "Savoir-être actuel : $softSkills.
                    Propose des savoir-être supplémentaires ou reformule ceux existants en fonction des exigences de l'offre d'emploi et pour qu'ils soient optimisés pour les ATS.
                    ";

            default:
                return $generalPrompt . "Optimise ce champ pour les ATS en prenant en compte l'offre d'emploi. Contenu : {$fieldContent['content']}.";
        }
    }
}
