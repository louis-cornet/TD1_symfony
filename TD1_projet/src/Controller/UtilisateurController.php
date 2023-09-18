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

class UtilisateurController extends AbstractController
{
    #[Route('/inscription', name: 'inscription',  methods: ["GET", "POST"])]
    public function inscription(UtilisateurManagerInterface $utilisateurManager,FlashMessageHelperInterface $flashMessageHelperInterface, Request $request, EntityManagerInterface $entityManager): Response
    {
        $inscriptionFrom = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $inscriptionFrom, [
            'method' => 'POST',
            'action' => $this->generateURL('inscription')
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inscriptionFrom);
            $entityManager->flush();
            $this ->addFlash("success", "Vous etes inscris");
            return $this->redirectToRoute('inscription');
        }

        $plainPassword = $form["plainPassword"]->getData();
        $fichierPhotoProfil = $form["fichierPhotoProfil"]->getData();

        $utilisateurManager->proccessNewUtilisateur($inscriptionFrom, $plainPassword, $fichierPhotoProfil);

        return $this->render("utilisateur/inscription.html.twig", ["form" => $form]);
    }
}
