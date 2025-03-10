<?php

namespace App\Controller;

use App\Entity\Poi;
use App\Form\UserType;
use App\Form\PoiFormType;
use App\Form\LanguagesType;
use App\Form\UserPoiFormType;
use App\Service\UploaderService;
use App\Form\UserCompleteBeingFormType;
use App\Repository\PoiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route(path: '/profil', name: 'app_user_')]
final class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route(name: 'profil', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        UploaderService $us,
        UserPasswordHasherInterface $uphi
    ): Response {

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pwd = $uphi->isPasswordValid($user, $form->get('password')->getData());
            if ($pwd) {
                $image = $form->get('image')->getData();
                if ($image != null) {
                    $user->setImage(
                        $us->uploadFile($image, $user->getImage())
                    );
                }

                $this->em->persist($user);
                $this->em->flush();

                // Redirection avec flash message
                $this->addFlash('success', 'Votre profil à été mis à jour');
            } else {
                $this->addFlash('error', 'Une erreur est survenue');
            }

            return $this->redirectToRoute('app_user_profil');
        }

        if (!$user->isVerified()) {
            $this->addFlash('warning', 'Validez votre email !');
        }

        if ($user->getSubscription() !== null) {
            $subs = $user->getSubscription();
            $now = new \DateTime();

            $remove = false;

            if (!$subs->isActive()) {
                $dateMax = (clone $subs->getCreatedAt())->modify('+20 minutes');
                $remove = $now > $dateMax;
            } else {
                $subsEnd = (clone $subs->getUpdatedAt())->modify('+1 month');

                $remove = $now > $subsEnd;
            }

            if ($remove) {
                $this->em->remove($subs);
                $this->em->flush();
            }
        }

        return $this->render('user/index.html.twig', [
            'userForm' => $form,
            'user' => $user
        ]);
    }

    #[Route('/completer/identite', name: 'complete_identity', methods: ['GET', 'POST'])]
    public function completeIdentity(Request $request): Response
    {
        $firstname = $request->getPayload()->get('firstname');
        $lastname = $request->getPayload()->get('lastname');
        $born = $request->getPayload()->get('born');
        $phone = $request->getPayload()->get('phone');
        $city = $request->getPayload()->get('city');
        $postal_code = $request->getPayload()->get('postal_code');

        if (!empty($firstname) && !empty($lastname) && !empty($born) && !empty($phone) && !empty($city) && !empty($postal_code)) {
            $user = $this->getUser();
            $user->setFirstname($firstname)
                ->setLastname($lastname)
                ->setBorn($born)
                ->setPhone($phone)
                ->setCity($city)
                ->setPostalCode($postal_code);
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', "Il vous reste à compléter les informations personnelles du cv pour terminer votre profil");
        } else {
            $this->addFlash('error', 'Vous devez remplir tous les champs');
        }

        return $this->redirectToRoute('app_homepage');
    }

    #[Route('/completer/competences_passions', name: 'complete_competences', methods: ['GET', 'POST'])]
    public function completeBeing(Request $request): Response
    {

        $form = $this->createForm(UserCompleteBeingFormType::class, $this->getUser());
        $form->handleRequest($request);
        $licences = $form->get('licences')->getData();
        $languages = $form->get('languages')->getData();

        // dd($licences, $languages);

        if (!empty($licences) && !empty($languages)) {
            $user = $this->getUser();
            $user->setLicences($licences)
                ->setLanguages($languages)
            ;
            $this->em->persist($user);
            $this->em->flush();

            if (!$user->isVerified()) {
                $this->addFlash('success', "Il ne vous reste qu'à vérifier votre email pour compléter votre profil");
            } else {
                $this->addFlash('success', 'Votre profil est complété');
            }
        } else {
            $this->addFlash('error', 'Vous devez remplir tous les champs');
        }

        return $this->redirectToRoute('app_user_profil');
    }

    #[Route('/editer/langages', name: 'languages_edit', methods: ['GET', 'POST'])]
    public function languagesEdit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(LanguagesType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Vos langues ont été mises à jour.');
            return $this->redirectToRoute('app_user_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/languages_edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/editer/centres-d-interets', name: 'poi_edit', methods: ['GET', 'POST'])]
    public function poiEdit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserPoiFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Vos centres d\'intérêts ont été mises à jour.');
            return $this->redirectToRoute('app_user_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/poi_edit.html.twig', [
            'form' => $form,
        ]);
    }

    // #[Route('/editer/centres-d-interets', name: 'poi_edit', methods: ['GET', 'POST'])]
    // public function poiEdit(Request $request, PoiRepository $pR): Response
    // {
    //     $user = $this->getUser();
    //     $poi = new Poi();
    //     $form = $this->createForm(PoiFormType::class, $poi);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $poiExisting = $pR->findOneByName($poi->getName());
    //         if ($poiExisting) {
    //             $poi = $poiExisting;
    //         } else {
    //             $this->em->persist($poi);
    //         }
    
    //         if (!$user->getPois()->contains($poi)) {
    //             $poi->addUser($user);
    //             $user->addPoi($poi);
    //         }
    //         $this->em->persist($user);
    //         $this->em->flush();

    //         $this->addFlash('success', 'Vos centres d\'intérêts ont été mises à jour.');
    //         return $this->redirectToRoute('app_user_profil', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('user/poi_edit.html.twig', [
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{ref}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request): Response
    {
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $this->em->remove($user);
            $this->em->flush();
        }

        $this->addFlash('success', 'Votre compte a bien été supprimé !');
        return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/cv1', name: 'cv1', methods: ['GET'])]
    public function verify(Request $request): Response
    {
        $jsonCv = '{
            "title": {
                "title": "Ingénieur DevOps Expérimenté en CI/CD et Test Automation"
            },
            "introduction": {
                "introduction": "Ingénieur DevOps chevronné avec 3 ans d\'expérience dans l\'automatisation des tests, l\'intégration logicielle et la mise en place de pipelines CI/CD GitLab et Jenkins."
            },
            "formation": [
                {
                    "title": "Master en Informatique",
                    "organization": "Université de Paris",
                    "description": "Formation approfondie en développement logiciel et architecture des systèmes.",
                    "city": "Paris",
                    "postal_code": "75000",
                    "country": "France",
                    "date_start": "2015-09-01",
                    "date_end": "2017-06-30",
                    "is_graduated": true,
                    "level": "Master",
                    "skills": ["C#", "Python", "Cybersécurité"]
                }
            ],
            "experience": [
                {
                    "title": "Ingénieur DevOps",
                    "organization": "Médiane Système",
                    "description": "Mise en place de pipelines CI/CD sous GitLab, optimisation des processus de déploiement et gestion des environnements de développement.",
                    "city": "Paris",
                    "postal_code": "75000",
                    "country": "France",
                    "date_start": "2019-01-01",
                    "date_end": "2022-06-30",
                    "skills": ["CI/CD", "Jenkins", "Test Automation"]
                }
            ],
            "skills": ["CI/CD", "Jenkins", "Test Automation"],
            "softskills": ["Travail en équipe", "Leadership", "Communication efficace"]
        }';

        // Décoder le JSON en tableau PHP
        $cv = json_decode($jsonCv, true);

     
        // Passer le tableau à Twig
        return $this->render('cv/cv_template.html.twig', [
            "cv" => $cv,
           
        ]);
    }
       
}
