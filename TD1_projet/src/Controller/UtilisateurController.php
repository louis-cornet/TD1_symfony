<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Service\FlashMessageHelperInterface;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UtilisateurController extends AbstractController
{
    #[Route('/inscription', name: 'inscription',  methods: ["GET", "POST"])]
    public function inscription(UtilisateurManagerInterface $utilisateurManager, Request $request, EntityManagerInterface $entityManager, FlashMessageHelperInterface $flashMessageHelperInterface): Response
    {
        $inscriptionFrom = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $inscriptionFrom, [
            'method' => 'POST',
            'action' => $this->generateURL('inscription')
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form["plainPassword"]->getData();
            $fichierPhotoProfil = $form["fichierPhotoProfil"]->getData();
            $utilisateurManager->proccessNewUtilisateur($inscriptionFrom, $plainPassword, $fichierPhotoProfil);
            $entityManager->persist($inscriptionFrom);
            $entityManager->flush();
            $this ->addFlash("success", "Vous etes inscris");
            return $this->redirectToRoute('inscription');
        }
        $flashMessageHelperInterface->addFormErrorsAsFlash($form);
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('feed');
        }else{
            return $this->render("utilisateur/inscription.html.twig", ["form" => $form]);
        }
    }

    #[Route('/connexion', name: 'connexion', methods: ['GET', 'POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils) : Response {
        $lastUsername = $authenticationUtils->getLastUsername();

        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('feed');
        }else{
            return $this->render('utilisateur/connexion.html.twig', ["lastUserName" => $lastUsername]);
        }
    }

    #[Route('/deconnexion', name: 'deconnexion', methods: ['POST'])]
    public function routeDeconnexion(): never
    {
        //Ne sera jamais appelée
        throw new \Exception("Cette route n'est pas censée être appelée. Vérifiez security.yaml");
    }

}
