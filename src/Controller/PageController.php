<?php

namespace App\Controller;

use App\Service\LoginHistoryService;
use App\Form\UserCompleteBeingFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PageController extends AbstractController
{

    #[Route('/', name: 'app_homepage', methods: ['GET'])]
    public function index(Request $request, LoginHistoryService $lHS): Response
    {
        if (!$this->getUser()) {
            return $this->render('page/homepage.html.twig');
        } else {
            $requestArray = [
                "fromLogin" => $this->getParameter('APP_URL') . $this->generateUrl('app_login'),
                "referer" => $request->headers->get('referer'), // permet de savoir d'où on vient
                "user-agent" => $request->headers->get('user-agent'),
                "ip" => $request->getClientIp(),
            ];

            if ($requestArray['referer'] === $requestArray['fromLogin']) {
                $lHS->addHistory($this->getUser(), $requestArray['user-agent'], $requestArray['ip']);
            }
            // dd(!$this->getUser()->isComplete());

            if ($this->getUser()->isComplete() === false) {
                return $this->render('user/complete_identity.html.twig');
            }
            if ($this->getUser()->isComplete() === true  && $this->getUser()->isComplete2() === false) {
                $UserCompleteBeingFormType = $this->createForm(UserCompleteBeingFormType::class, $this->getUser());
                return $this->render('user/complete_competences.html.twig', [
                    'form' => $UserCompleteBeingFormType,
                ]);
            }

            return $this->redirectToRoute('app_user_profil');
        }
    }
}
