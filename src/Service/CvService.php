<?php

namespace App\Service;

use App\Entity\Cv;
use App\Entity\Offer;
use App\Repository\CvRepository;
use App\Repository\ExperienceRepository;
use App\Repository\FormationRepository;
use App\Repository\SkillRepository;
use App\Repository\SoftSkillRepository;
use Symfony\Component\HttpFoundation\Request;

class CvService
{
    private $cvRepository;
    private $formationRepository;
    private $experienceRepository;
    private $skillRepository;
    private $softSkillRepository;
    private $aiService;
    private $pdfGenerationService;

// Injecter les dépendances via le constructeur
    public function __construct(
        CvRepository         $cvRepository,
        FormationRepository  $formationRepository,
        ExperienceRepository $experienceRepository,
        SkillRepository      $skillRepository,
        SoftSkillRepository  $softSkillRepository,
        AIService            $aiService,
        PdfGenerationService $pdfGenerationService,
    )
    {
        $this->cvRepository = $cvRepository;
        $this->formationRepository = $formationRepository;
        $this->experienceRepository = $experienceRepository;
        $this->skillRepository = $skillRepository;
        $this->softSkillRepository = $softSkillRepository;
        $this->aiService = $aiService;
        $this->pdfGenerationService = $pdfGenerationService;
    }

// Créer un CV pour l'utilisateur
    public function createCv(Cv $cv, Request $request)
    {
        $user = $this->getUser();
        $cv->setUser($user);
        $cv->setEmail($user->getEmail());

        $formations = $this->formationRepository->findByUser($user);
        $experiences = $this->experienceRepository->findByUser($user);
        $skills = $this->skillRepository->findByUser($user);
        $softSkills = $this->softSkillRepository->findByUser($user);

        foreach ($formations as $formation) {
            $cv->addFormation($formation);
        }

        foreach ($experiences as $experience) {
            $cv->addExperience($experience);
        }

        foreach ($skills as $skill) {
            $cv->addSkill($skill);
        }

        foreach ($softSkills as $softSkill) {
            $cv->addSoftSkill($softSkill);
        }

        $offer = $this->getOfferFromRequest($request);
        if ($offer) {
            $cv->setOffer($offer);
        }

        $this->cvRepository->save($cv);

        $aiResult = $this->aiService->generateCV($cv);
        $cv->setOptimizedContent($aiResult->getOptimizedContent());

        $this->cvRepository->save($cv);

        return $cv;
    }

    private function getOfferFromRequest(Request $request): ?Offer
    {
        $offerUrl = $request->get('offer_url');
        if ($offerUrl) {
            return new Offer([
                'url' => $offerUrl,
                'title' => $request->get('offer_title'),
                'content' => $request->get('offer_content'),
            ]);
        }
        return null;
    }




// Générer le PDF du CV
    public function generatePdf(Cv $cv): string
    {
        return $this->pdfGenerationService->generatePdf($cv);
    }

// Mettre à jour un CV existant
    public function updateCv(Cv $cv, Request $request)
    {
// Mettre à jour les informations du CV
// Cela pourrait être une méthode d'update du cv dans la base de données.
// Après les modifications, vous pouvez aussi repasser par l'optimisation de l'IA si nécessaire.
        $aiResult = $this->aiService->generateCV($cv);
        $cv->setOptimizedContent($aiResult->getOptimizedContent());

// Enregistrer les changements
        $this->cvRepository->save($cv);

        return $cv;
    }

// Récupérer les CV d'un utilisateur
    public function getUserCvs($user)
    {
        return $this->cvRepository->findBy(['user' => $user]);
    }
}
