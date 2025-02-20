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

        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' =>
                        'Tu es un expert en optimisation de CV pour les systèmes de suivi des candidatures (ATS) en français. 
                        Ta mission est de fournir des suggestions précises et pertinentes pour améliorer chaque section du CV en fonction de l\'offre d\'emploi fournie. 
                        Respecte ces règles :
                        1. Propose uniquement des modifications concrètes et directes.
                        2. Utilise un format structuré pour chaque suggestion (JSON).
                        3. Quand tu renvois les infos, renvoi un objet JSON avec les clés "title" et "introduction" (ou les clés appropriées pour le type de champ).
                        4. Concentre-toi sur l\'adaptation à l\'offre d\'emploi et l\'optimisation pour les ATS.
                        5. Ne donne pas d\'explications supplémentaires.'
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 500,
            ]);
        } catch (\Exception $e) {
            return [
                'error' => 'Une erreur est survenue lors de la communication avec l\'API : ' . $e->getMessage()
            ];
        }

        // Décode la réponse JSON de l'IA
        $suggestions = json_decode($response['choices'][0]['message']['content'], true);

        // Vérifie si le décodage a réussi et si $suggestions est un tableau
        if (json_last_error() === JSON_ERROR_NONE && is_array($suggestions)) {
            return $suggestions;
        } else {
            // En cas d'erreur de décodage JSON ou si ce n'est pas un tableau, renvoie un message d'erreur
            return [
                'error' => 'Erreur lors du décodage de la réponse JSON de l\'IA ou format de réponse incorrect.',
                'details' => $response['choices'][0]['message']['content'] // Ajoute les détails de la réponse pour débogage
            ];
        }
    }

    // Générer le prompt pour analyser le champ en fonction du type
    private function generatePrompt(string $fieldType, array $fieldContent, string $jobTitle, string $jobDescription): string
    {
        $generalPrompt = "L'offre d'emploi suivante est à optimiser pour les ATS :
        Titre de l'offre d'emploi : $jobTitle
        Description de l'offre d'emploi : $jobDescription

        Optimise les informations suivantes pour le champ : $fieldType. ";

        switch ($fieldType) {
            case 'title':
                return $generalPrompt .
                    "Titre du CV actuel : {$fieldContent['title']}
                    Offre d'emploi : $jobTitle
                    
                    Tâche : Propose un titre de CV optimisé qui :
                    1. Correspond aux mots-clés de l'offre d'emploi
                    2. Met en avant les compétences principales recherchées
                    3. Est concis et impactant (maximum 5-7 mots)
                    
                    Format de réponse (JSON) :
                    {
                      \"[Votre suggestion]\"
                    }";

            case 'introduction':
                return $generalPrompt .
                    "Introduction actuelle : {$fieldContent['introduction']}
                    Offre d'emploi : $jobTitle
                    Description : $jobDescription
                    
                    Tâche : Crée une introduction optimisée qui :
                    1. Résume en 2-3 phrases le profil du candidat
                    2. Intègre les mots-clés principaux de l'offre d'emploi
                    3. Met en avant la valeur ajoutée du candidat pour le poste
                    
                    Format de réponse (JSON) :
                    {
                      \"[Votre suggestion]\"
                    }";

           case 'formation': // Formations
                $formationsText = '';
                foreach ($fieldContent as $formation) {
                    $formationsText .= $this->formatFormation($formation);
                }
                return $generalPrompt . $formationsText . "
                Informations actuelles :
                $formationsText
                
                Offre d'emploi : $jobTitle
                Description : $jobDescription
                
                Tâche : Pour chaque élément, propose des améliorations qui :
                1. Mettent en avant les compétences et réalisations pertinentes pour l'offre
                2. Utilisent des verbes d'action et des mots-clés de l'offre d'emploi
                3. Quantifient les résultats lorsque c'est possible
                4. Ajoute les skills associé à la formation, si l'utilisateur n'a pas assoscié de skills ajoutes-en 10
                        
                Format de réponse (JSON) :
                {
                    \"title\": \"[Votre suggestion]\",
                    \"organization\": \"[Votre suggestion]\",
                    \"description\": \"[Votre suggestion]\",
                    \"city\": \"[Votre suggestion]\",
                    \"postal_code\": \"[Votre suggestion]\",
                    \"country\": \"[Votre suggestion]\",
                    \"date_start\": \"[Votre suggestion]\",
                    \"date_end\": \"[Votre suggestion]\",
                    \"is_graduated\": \"[Votre suggestion]\",
                    \"level\": \"[Votre suggestion]\",
                    \"skills\": [
                            \"[Votre suggestion]\"
                        ]
                }";

            case 'experience': // Expériences professionnelles
                $experiencesText = '';
                foreach ($fieldContent as $experience) {
                    $experiencesText .= $this->formatExperience($experience);
                }
                return $generalPrompt . $experiencesText . "
                Informations actuelles :
                $experiencesText
                
                Offre d'emploi : $jobTitle
                Description : $jobDescription
                
                Tâche : Pour chaque élément, propose des améliorations qui :
                1. Mettent en avant les compétences et réalisations pertinentes pour l'offre
                2. Utilisent des verbes d'action et des mots-clés de l'offre d'emploi
                3. Quantifient les résultats lorsque c'est possible
                4. Ajoute les skills associé à l'éxpérience, si l'utilisateur n'a pas assoscié de skills ajoutes-en 10

                Format de réponse (JSON) :
                {
                    \"title\": \"[Votre suggestion]\",
                    \"organization\": \"[Votre suggestion]\",
                    \"description\": \"[Votre suggestion]\",
                    \"city\": \"[Votre suggestion]\",
                    \"postal_code\": \"[Votre suggestion]\",
                    \"country\": \"[Votre suggestion]\",
                    \"date_start\": \"[Votre suggestion]\",
                    \"date_end\": \"[Votre suggestion]\",
                    \"skills\": [
                            \"[Votre suggestion]\"
                        ]
                }";

            case 'skills': // Compétences (Skills)
                $skills = implode(", ", $fieldContent['skills']);  // Liste des compétences
                return $generalPrompt .
                    "Compétences : $skills
                    Offre d'emploi : $jobTitle
                    Description : $jobDescription
                    
                    Tâche : 
                    1. Identifie les compétences les plus pertinents pour l'offre
                    2. Suggère des reformulations pour mieux correspondre aux termes de l'offre
                    3. Propose jusqu'à 3 compétences supplémentaires si pertinent
                    
                    Format de réponse (JSON) :
                    {
                        [
                            \"[Votre suggestion]\",
                            \"[Votre suggestion]\",
                            \"[Votre suggestion]\",
                            \"[Votre suggestion]\"
                        ]
                    }";

            case 'softskills': // Savoir-être (Soft Skills)
                $softSkills = implode(", ", $fieldContent['softskills']);  // Liste des savoir-être
                return $generalPrompt .
                    "Savoir-être actuels : $softSkills
                    Offre d'emploi : $jobTitle
                    Description : $jobDescription
                    
                    Tâche : 
                    1. Identifie les savoir-être les plus pertinents pour l'offre
                    2. Suggère des reformulations pour mieux correspondre aux termes de l'offre
                    3. Propose jusqu'à 3 savoir-être supplémentaires si pertinent
                    
                    Format de réponse (JSON) :
                    {
                        [
                            \"[Votre suggestion]\",
                            \"[Votre suggestion]\",
                            \"[Votre suggestion]\"
                        ]
                    }";

            default:
                return $generalPrompt . "Optimise ce champ pour les ATS en prenant en compte l'offre d'emploi. Contenu : {$fieldContent['content']}.";
        }
    }

    // Fonction générique pour formater l'expérience et la formation
    private function formatExperience(array $data): string
    {
        $formatter = function($key, $label) use ($data) {
            return isset($data[$key]) && $data[$key] !== ''
                ? "- $label : {$data[$key]}\n"
                : '';
        };

        $dateFormatter = function($start, $end) {
            return $this->formatDateRange($start, $end);
        };

        return
            $formatter('title', 'Titre') .
            $formatter('organization', 'Organisation') .
            $formatter('description', 'Description') .
            $formatter('city', 'Ville') .
            $formatter('postal_code', 'Code postal') .
            $formatter('country', 'Pays') .
            "- Période : " . $dateFormatter($data['date_start'] ?? '', $data['date_end'] ?? '') . "\n";
    }

    private function formatFormation(array $data): string
    {
        $baseFormat = $this->formatExperience($data);

        $isGraduated = isset($data['is_graduated']) && $data['is_graduated'] ? 'Oui' : 'Non';
        $level = $data['level'] ?? '';

        return $baseFormat .
            ($level ? "- Niveau : $level\n" : '') .
            "- Diplômé : $isGraduated\n";
    }

    private function formatDateRange($start, $end): string
    {
        $start = $start ? date('m/Y', strtotime($start)) : '';
        $end = $end ? date('m/Y', strtotime($end)) : 'Présent';
        return "$start - $end";
    }
}
