<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends AbstractController
{
    #[Route('/hello', name: 'hello_get', methods: ["GET"])]
    public function helloGet(): Response
    {
        $contenu = "Hello world";
        return $this->render("demo/demo1.html.twig");
    }

    #[Route('/hello/{name}', name: 'hello_get2', methods: ["GET"])]
    public function helloGet2($name): Response
    {
        //$contenu = "Hello world " . $name;
        //return new Response($contenu);
        return $this->render("demo/demo2.html.twig", ["name" => $name]);
    }

    #[Route('/courses', name: 'courses_get', methods: ["GET"])]
    public function coursesGet(): Response
    {
        $courses = array(
            'produit 1' => 'oeufs',
            'produit 2' => 'pain',
            'produit 3' => 'lait',
            'produit 4' => 'Livre sur Symfony'
        );

        $this->addFlash('error', 'La resourse n"est pas presente');

        return $this->render("demo/demo3.html.twig", ["courses" => $courses]);
    }
}
