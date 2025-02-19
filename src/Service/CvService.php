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
        set_time_limit(120);  // Définir un temps d'exécution plus long pour les appels API

        $prompt = $this->generatePrompt($fieldType, $fieldContent, $jobTitle, $jobDescription);

        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-4',  // Utilisation du modèle GPT-4
                'messages' => [
                    ['role' => 'system', 'content' =>
                        'Tu es un expert en optimisation de CV pour les systèmes de suivi des candidatures (ATS). Ta mission est de fournir des suggestions précises et pertinentes pour améliorer chaque section du CV en fonction de l\'offre d\'emploi fournie. Respecte ces règles :
                        1. Propose uniquement des modifications concrètes et directes.
                        2. Utilise un format structuré pour chaque suggestion.
                        3. Concentre-toi sur l\'adaptation à l\'offre d\'emploi et l\'optimisation pour les ATS.
                        4. Ne donne pas d\'explications supplémentaires.
                        5. Si aucune modification n\'est nécessaire, indique-le clairement
                        '
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 500,  // Limite du nombre de tokens pour la réponse
            ]);
        } catch (\Exception $e) {
            return [
                'error' => 'Une erreur est survenue lors de la communication avec l\'API : ' . $e->getMessage()
            ];
        }

        // Suggestions d'améliorations pour ce champ
        $suggestions = $response['choices'][0]['message']['content'];

        return [
            'original' => $fieldContent,  // Contenu original du champ
            'suggestions' => $suggestions  // Suggestions d'améliorations proposées par l'IA
        ];
    }

    // Générer le prompt pour analyser le champ en fonction du type (compétence, expérience, etc.)
    private function generatePrompt(string $fieldType, array $fieldContent, string $jobTitle, string $jobDescription): string
    {
        // Partie générale : l'IA doit toujours être informée du titre et du contenu de l'offre d'emploi
        $generalPrompt = "
        L'offre d'emploi suivante est à optimiser pour les ATS :
        Titre de l'offre d'emploi : $jobTitle
        Description de l'offre d'emploi : $jobDescription

        Maintenant, optimise les informations suivantes pour le champ : $fieldType.
        ";

        // Ajout des sections spécifiques selon le type de champ (formation, expérience, etc.)
        switch ($fieldType) {
            case 'title': // Titre du CV
                return $generalPrompt .
                    "Titre du CV actuel : {$fieldContent['title']}
                    Offre d'emploi : $jobTitle
                    
                    Tâche : Propose un titre de CV optimisé qui :
                    1. Correspond aux mots-clés de l'offre d'emploi
                    2. Met en avant les compétences principales recherchées
                    3. Est concis et impactant (maximum 5-7 mots)
                    
                    Format de réponse :
                    Titre optimisé : [Votre suggestion]
                    ";

            case 'introduction': // Introduction / Phrase d'accroche
                return $generalPrompt .
                    "Introduction actuelle : {$fieldContent['introduction']}
                    Offre d'emploi : $jobTitle
                    Description : $jobDescription
                    
                    Tâche : Crée une introduction optimisée qui :
                    1. Résume en 2-3 phrases le profil du candidat
                    2. Intègre les mots-clés principaux de l'offre d'emploi
                    3. Met en avant la valeur ajoutée du candidat pour le poste
                    
                    Format de réponse :
                    Introduction optimisée : [Votre suggestion]
                    ";

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
                
                Format de réponse :
                - [Nom du champ et numéro] : [Suggestion optimisée]
                (Répéter pour chaque champ nécessitant une optimisation)
                ";

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
                
                Format de réponse :
                - [Nom du champ et numéro] : [Suggestion optimisée]
                (Répéter pour chaque champ nécessitant une optimisation)
                ";

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
                    
                    Format de réponse :
                    Compétences optimisés :
                    - [Compétence 1]
                    - [Compétence 2]
                    ...
                    Suggestions supplémentaires :
                    - [Nouvelle compétence 1]
                    - [Nouvelle compétence 2]
                    - [Nouvelle compétence 3]
                    ";

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
                    
                    Format de réponse :
                    Savoir-être optimisés :
                    - [Savoir-être 1]
                    - [Savoir-être 2]
                    ...
                    Suggestions supplémentaires :
                    - [Nouvelle savoir-être 1]
                    - [Nouvelle savoir-être 2]
                    - [Nouvelle savoir-être 3]
                    ";

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
