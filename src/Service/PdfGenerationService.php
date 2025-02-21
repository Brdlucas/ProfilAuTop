<?php

namespace App\Service;

use App\Entity\Cv;
use Dompdf\Dompdf;

class PdfGenerationService
{
    public function generatePdf(Cv $cv)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml('<h1>' . $cv->getTitle() . '</h1><p>' . $cv->getIntroduction() . '</p>');  // Plus de contenu ici

        $dompdf->render();
        $output = $dompdf->output();

// Sauvegarder ou envoyer le PDF
        return $output;
    }
}
