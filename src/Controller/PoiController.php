<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Form\PoiType;
use App\Repository\PoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profil/poi')]
final class PoiController extends AbstractController{

    #[Route(name: 'app_poi', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $poi = new Poi();
        $form = $this->createForm(PoiType::class, $poi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $poi->addUser($user);
            $entityManager->persist($poi);
            $entityManager->flush();

            $this->addFlash( 'success', 'Vos centres d\'intérêts ont été mis à jour.');
            return $this->redirectToRoute('app_user_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('poi/new.html.twig', [
            'poi' => $poi,
            'form' => $form,
        ]);
    }

}
