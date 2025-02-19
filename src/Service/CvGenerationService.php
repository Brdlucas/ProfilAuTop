<?php

namespace App\Service;

use App\Entity\Cv;
use App\Entity\Offer;
use App\Entity\User;
use App\Repository\CvRepository;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\File\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CvGenerationService
{
    private $client;
    private $mailer;
    private $cvRepository;
    private $offerRepository;

    public function __construct(
        HttpClientInterface $client,
        MailerInterface     $mailer,
        CvRepository        $cvRepository,
        OfferRepository     $offerRepository
    )
    {
        $this->client = $client;
        $this->mailer = $mailer;
        $this->cvRepository = $cvRepository;
        $this->offerRepository = $offerRepository;
    }

// Fonction pour générer un CV en fonction de l'offre et des informations de l'utilisateur
    public function generateCv(User $user, Offer $offer, array $cvData): Response
    {
// Créer un nouveau CV basé sur l'offre et l'utilisateur
        $cv = new Cv();
        $cv->setCreator($user);
        $cv->setOffer($offer);
        $cv->setRef(uniqid('CV-')); // Générer un ref unique pour le CV
        $cv->setTitle($cvData['title']);
        $cv->setIntroduction($cvData['introduction']);
        $cv->setLink($cvData['link']);
        $cv->setEmail($cvData['email']);
// Autres champs du CV...

// Intégration de l'IA pour optimiser le CV pour les ATS
        $optimizedCv = $this->optimizeCvForATS($cv, $offer);

// Enregistrement du CV dans la base de données
        $this->cvRepository->save($cv);

// Générer le fichier PDF du CV
        $pdfFile = $this->generatePdf($optimizedCv);

// Retourner la réponse avec le fichier PDF
        return new BinaryFileResponse($pdfFile, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="cv_' . $cv->getRef() . '.pdf"'
        ]);
    }

// Optimiser le CV pour les ATS avec l'IA (OpenAI)
    private function optimizeCvForATS(Cv $cv, Offer $offer): string
    {
// Préparer les données pour l'API OpenAI
        $prompt = $this->generateAtSPrompt($cv, $offer);

// Appel à l'API OpenAI
        $response = $this->client->request('POST', 'https://api.openai.com/v1/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . getenv('OPENAI_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'max_tokens' => 2000,
                'temperature' => 0.5,
            ]
        ]);

// Traitement de la réponse de l'IA
        $responseContent = $response->toArray();
        $optimizedCv = $responseContent['choices'][0]['text'];

        return $optimizedCv;
    }

// Générer le prompt pour OpenAI
    private function generateAtSPrompt(Cv $cv, Offer $offer): string
    {
// Ici, on assemble les informations du CV et de l'offre pour que l'IA puisse les utiliser
        return "Optimise ce CV pour passer les systèmes ATS, en fonction de l'offre suivante :
Offre: " . $offer->getContent() . "
CV: " . $cv->getIntroduction() . " " . $cv->getTitle() . " " . $cv->getEmail();
    }

// Générer un fichier PDF à partir du CV optimisé
    private function generatePdf(string $optimizedCv): string
    {
// Utiliser une bibliothèque comme TCPDF ou Dompdf pour générer un PDF
// Cette partie pourrait inclure le formatage et l'insertion du contenu optimisé dans un modèle de CV
// Par exemple avec Dompdf :
// $pdf = new Dompdf();
// $pdf->loadHtml($optimizedCv);
// $pdf->render();
// return $pdf->output();
    }
}
