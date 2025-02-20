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

#[Route('/poi')]
final class PoiController extends AbstractController{
    #[Route(name: 'app_poi_index', methods: ['GET'])]
    public function index(PoiRepository $poiRepository): Response
    {
        return $this->render('poi/index.html.twig', [
            'pois' => $poiRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_poi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $poi = new Poi();
        $form = $this->createForm(PoiType::class, $poi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($poi);
            $entityManager->flush();

            return $this->redirectToRoute('app_poi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('poi/new.html.twig', [
            'poi' => $poi,
            'form' => $form,
        ]);
    }

}
