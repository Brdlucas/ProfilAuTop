<?php

namespace App\Tests\Service;

use App\Entity\Cv;
use App\Entity\Offer;
use App\Service\AIService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use PHPUnit\Framework\MockObject\MockObject;

class AIServiceTest extends TestCase
{
    private AIService $aiService;
    private MockObject $httpClientMock;

    protected function setUp(): void
    {
        // Création du mock pour HttpClientInterface
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);

        // Instanciation du service AIService avec le mock
        $this->aiService = new AIService(
            $this->httpClientMock,
            'https://api.openai.com/v1/generate-cv',  // URL de l'API OpenAI (exemple)
            'your_api_key'  // Clé API
        );
    }

    public function testGenerateCV(): void
    {
        // Créer un faux CV et une offre
        $cv = new Cv();
        $cv->setEmail('test@example.com');
        $cv->setTitle('Développeur PHP');
        $cv->setIntroduction('Développeur passionné avec 5 ans d\'expérience.');
        $cv->setSkills(['PHP', 'Symfony']); // Ajouter des compétences initiales

        $offer = new Offer();
        $offer->setTitle('Offre de Développeur PHP');
        $offer->setContent('Nous recherchons un développeur PHP expérimenté.');
        $cv->setOffer($offer);

        // Assurez-vous que l'array de compétences n'est pas vide avant de continuer
        $skills = $cv->getSkills();
        $this->assertNotEmpty($skills);  // Vérifie que le tableau de compétences n'est pas vide

        // Ajouter des compétences supplémentaires
        $cv->setSkills(['PHP', 'Symfony', 'MySQL']);

        // Simuler une réponse de l'API
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('toArray')->willReturn([
            'optimized_content' => 'Contenu optimisé',
            'suggestions' => [
                'Ajoutez des compétences en Symfony.',
                'Mettez à jour votre introduction.',
            ],
        ]);

        // Configurer le mock pour la méthode request
        $this->httpClientMock->method('request')
            ->willReturn($mockResponse);

        // Appeler la méthode generateCV et récupérer la réponse
        $result = $this->aiService->generateCV($cv);

        // Vérifier les assertions
        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertEquals('Contenu optimisé', $result->optimizedContent);
        $this->assertCount(2, $result->suggestions);
        $this->assertEquals('Ajoutez des compétences en Symfony.', $result->suggestions[0]);
    }

    public function testHandleErrorResponse(): void
    {
        // Créer un faux CV
        $cv = new Cv();
        $cv->setEmail('test@example.com');
        $cv->setTitle('Développeur PHP');

        // Simuler une réponse d'erreur de l'API
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(500);
        $mockResponse->method('getContent')->willReturn('Internal Server Error');

        // Configurer le mock pour la méthode request
        $this->httpClientMock->method('request')
            ->willReturn($mockResponse);

        // Vérifier que l'exception est bien lancée
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Erreur lors de la génération du CV : Internal Server Error');

        // Appeler la méthode generateCV
        $this->aiService->generateCV($cv);
    }
}
