<?php

namespace App\Service;

use App\Controller\PublicationController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
class FlashMessageHelper implements FlashMessageHelperInterface
{
    public function __construct(
        private RequestStack $requestStack
    /* Injection de RequestStack */
    ){}

    public function addFormErrorsAsFlash(FormInterface $form) : void
    {
        $errors = $form->getErrors(true);
        foreach ($errors as $error) {
            $errorMsg = $error->getMessage();

            $flashBag = $this->requestStack->getSession()->getFlashBag();
            $flashBag->add("error", $errorMsg);
        }
    }
}