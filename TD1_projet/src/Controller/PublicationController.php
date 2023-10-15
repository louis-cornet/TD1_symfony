<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\PublicationType;
use App\Service\FlashMessageHelper;
use App\Service\FlashMessageHelperInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function PHPUnit\Framework\never;

class PublicationController extends AbstractController
{
    #[Route('/', name: 'feed')]
    public function feed(FlashMessageHelperInterface $flashMessageHelperInterface, Request $request, PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
        $publicationFrom = new Publication();
        $form = $this->createForm(PublicationType::class, $publicationFrom, [
            'method' => 'POST',
            'action' => $this->generateURL('feed')
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if ($form->isValid()) {
                $publicationFrom->setAuteur($this->getUser());
                $entityManager->persist($publicationFrom);
                $entityManager->flush();
                $this->denyAccessUnlessGranted('ROLE_USER');
                $this->addFlash("success", "Success de création");
                return $this->redirectToRoute('feed');
            }else{
                $flashMessageHelperInterface->addFormErrorsAsFlash($form);
            }
        }

        $publications = $publicationRepository->findAllOrderedByDate();
        return $this->render("publication/feed.html.twig", ["publications" => $publications, "form" => $form]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/feedy/{id}', name: 'deletePublication ', options: ["expose" => true], methods: ['DELETE', 'POST'])]
    public function deletePublication (#[MapEntity] ?Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($publication) {
            if ($this->getUser()->getUserIdentifier() === $publication->getAuteur()->getUserIdentifier()) {
                $entityManager->remove($publication);
                $entityManager->flush();
                return new JsonResponse(null, 204);
            }
            return new JsonResponse(null, 403);
        }
        return new JsonResponse(null, 404);
    }


//    #[Route('/', name: 'base')]
//    public function base(Request $request): response
//    {
//        return $this->render("base.html.twig");
//    }
}
