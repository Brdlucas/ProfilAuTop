<?php

namespace App\Service;

use App\Entity\Cv;
use App\Entity\Offer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AIService
{
    private $httpClient;
    private $aiApiUrl;
    private $apiKey;

    // Injection des dépendances via le constructeur
    public function __construct(HttpClientInterface $httpClient, string $aiApiUrl, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->aiApiUrl = $aiApiUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * Génère un CV en interrogeant l'API AI.
     *
     * @param Cv $cv
     * @return \stdClass
     */
    public function generateCV(Cv $cv): \stdClass
    {
        $cvData = $this->extractCvData($cv);

        $payload = [
            'cv_data' => $cvData,
            'offer_data' => $cv->getOffer() ? $this->extractOfferData($cv->getOffer()) : null,
        ];

        // Effectuer la requête POST vers l'API
        $response = $this->httpClient->request('POST', $this->aiApiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'json' => $payload,
        ]);

        // Traiter la réponse de l'API
        return $this->handleResponse($response);
    }

    /**
     * Traite la réponse de l'API pour obtenir les données du CV généré.
     *
     * @param ResponseInterface $response
     * @return \stdClass
     */
    private function handleResponse(ResponseInterface $response): \stdClass
    {
        // Si la réponse est réussie, on retourne les données
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $data = $response->toArray();
            return $this->transformAiResponseToCvData($data);
        }

        // En cas d'erreur, on lance une exception avec le message d'erreur
        throw new \Exception('Erreur lors de la génération du CV : ' . $response->getContent(false));
    }

    /**
     * Extrait les données principales du CV.
     *
     * @param Cv $cv
     * @return array
     */
    private function extractCvData(Cv $cv): array
    {
        return [
            'email' => $cv->getEmail(),
            'title' => $cv->getTitle(),
            'introduction' => $cv->getIntroduction(),
            'formations' => $this->extractFormationsData($cv),
            'experiences' => $this->extractExperiencesData($cv),
            'skills' => $this->extractSkillsData($cv),
            'softSkills' => $this->extractSoftSkillsData($cv),
        ];
    }

    /**
     * Extrait les informations de formation du CV.
     *
     * @param Cv $cv
     * @return array
     */
    private function extractFormationsData(Cv $cv): array
    {
        $formations = [];
        foreach ($cv->getFormations() as $formation) {
            $formations[] = [
                'title' => $formation->getTitle(),
                'organization' => $formation->getOrganization(),
                'description' => $formation->getDescription(),
                'date_start' => $formation->getDateStart(),
                'date_end' => $formation->getDateEnd(),
            ];
        }
        return $formations;
    }

    /**
     * Extrait les informations des expériences professionnelles du CV.
     *
     * @param Cv $cv
     * @return array
     */
    private function extractExperiencesData(Cv $cv): array
    {
        $experiences = [];
        foreach ($cv->getExperiences() as $experience) {
            $experiences[] = [
                'title' => $experience->getTitle(),
                'organization' => $experience->getOrganization(),
                'description' => $experience->getDescription(),
                'date_start' => $experience->getDateStart(),
                'date_end' => $experience->getDateEnd(),
            ];
        }
        return $experiences;
    }

    /**
     * Extrait les compétences du CV.
     *
     * @param Cv $cv
     * @return array
     */
    private function extractSkillsData(Cv $cv): array
    {
        $skills = [];
        foreach ($cv->getSkills() as $skill) {
            // Si $skill est une chaîne, ajoutez-le directement
            if (is_string($skill)) {
                $skills[] = [
                    'name' => $skill,
                ];
            } elseif (is_object($skill) && method_exists($skill, 'getName')) {
                // Si $skill est un objet avec la méthode getName, appelez-le
                $skills[] = [
                    'name' => $skill->getName(),
                ];
            } else {
                // Gestion des cas où $skill n'est ni une chaîne ni un objet valide
                throw new \InvalidArgumentException('Compétence invalide');
            }
        }
        return $skills;
    }


    /**
     * Extrait les savoir-être (soft skills) du CV.
     *
     * @param Cv $cv
     * @return array
     */
    private function extractSoftSkillsData(Cv $cv): array
    {
        $softSkills = [];
        foreach ($cv->getSoftSkills() as $softSkill) {
            $softSkills[] = [
                'name' => $softSkill->getName(),
            ];
        }
        return $softSkills;
    }

    /**
     * Extrait les informations d'une offre d'emploi.
     *
     * @param Offer|null $offer
     * @return array|null
     */
    private function extractOfferData(?Offer $offer): ?array
    {
        if ($offer) {
            return [
                'url' => $offer->getUrl(),
                'title' => $offer->getTitle(),
                'content' => $offer->getContent(),
            ];
        }
        return null;
    }

    /**
     * Transforme la réponse de l'API en données exploitables pour le CV.
     *
     * @param array $aiResponse
     * @return \stdClass
     */
    private function transformAiResponseToCvData(array $aiResponse): \stdClass
    {
        $optimizedCv = new \stdClass();
        $optimizedCv->optimizedContent = $aiResponse['optimized_content'] ?? '';
        $optimizedCv->suggestions = $aiResponse['suggestions'] ?? [];

        return $optimizedCv;
    }

    /**
     * Analyse les suggestions retournées par l'IA pour améliorer le CV.
     *
     * @param array $suggestions
     */
    public function analyzeSuggestions(array $suggestions): void
    {
        foreach ($suggestions as $suggestion) {
            // Appliquer les modifications au CV ou afficher les recommandations
            // Par exemple : modifier le format, réorganiser les sections, ajouter des mots-clés
            // Implémenter votre logique d'analyse et de traitement des suggestions ici.
        }
    }
}
