<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Form\SubscriptionType;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/abonnement')]
final class SubscriptionController extends AbstractController{
    #[Route(name: 'app_subscription_index', methods: ['GET'])]
    public function index(SubscriptionRepository $subscriptionRepository): Response
    {
        return $this->render('subscription/index.html.twig', [
            'subscriptions' => $subscriptionRepository->findAll(),
        ]);
    }
}
