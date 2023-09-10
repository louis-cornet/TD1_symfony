<?php

namespace App\Controller;

use App\Form\PublicationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publication;
use App\Repository\PublicationRepository;
class PublicationController extends AbstractController
{
    #[Route('/', name: 'feed')]
    public function feed(Request $request, PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
        $publicationFrom = new Publication();
        $form = $this->createForm(PublicationType::class, $publicationFrom, [
            'method' => 'POST',
            'action' => $this->generateURL('feed')
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publicationFrom);
            $entityManager->flush();

            return $this->redirectToRoute('feed');
        }

        $errors = $form->getErrors(true);
        foreach ($errors as $error) {
            $errorMsg = $error->getMessage();
        }

        $publications = $publicationRepository->findAllOrderedByDate();
        return $this->render("publication/feed.html.twig", ["publications" => $publications, "form" => $form, "errors" => $errors]);
    }
}
