<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publication;
class PublicationController extends AbstractController
{
    #[Route('/', name: 'feed')]
    public function feed(): Response
    {
        $publication1 = new Publication();
        $publication1->setMessage("Coucou");
        $publication1->setDatePublication(new \DateTime());

        $publication2 = new Publication();
        $publication2->setMessage("Je suis");
        $publication2->setDatePublication(new \DateTime());

        $publication3 = new Publication();
        $publication3->setMessage("un Bot");
        $publication3->setDatePublication(new \DateTime());

        $publications =  array($publication1, $publication2, $publication3);

        return $this->render("publication/feed.html.twig", ["publications" => $publications]);
    }
}
