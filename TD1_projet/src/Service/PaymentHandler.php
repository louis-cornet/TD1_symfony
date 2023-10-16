<?php

namespace App\Service;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class PaymentHandler
{
    public function __construct(
        private RouterInterface $router,
        private UtilisateurManagerInterface $utilisateurManager,
        private EntityManagerInterface $entityManager
    ){}

    //Génère et renvoie un lien vers Stripe afin de finaliser l'achat du statut Premium pour l'utilisateur passé en paramètre.
    public function getPremiumCheckoutUrlFor(Utilisateur $utilisateur)  : string {
        $paymentData = [
            'mode' => 'payment',
            'payment_intent_data' => ['capture_method' => 'manual', 'receipt_email' => $utilisateur->getAdresseEmail()],
            'customer_email' => $utilisateur->getAdresseEmail(),
            'success_url' => $this->generate('feed', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generate('premiumInfos', [], UrlGeneratorInterface::ABSOLUTE_URL),
            "metadata" => ["data" => $utilisateur->getUserIdentifier()],
            "line_items" => [
                [
                    "price_data" => [
                        "currency" => "eur",
                        "product_data" => ["name" => 'The Feed Premium'],
                        "unit_amount" => ''
                    ],
                    "quantity" => 1
                ],
                [
                    '...'
                ]
            ]
        ];
        Stripe::setApiKey(...);
        $stripeSession = Session::create($paymentData);
        $url = $stripeSession->url;

        //Pour générer une URL absolue
        $url = $router->generate('feed', [], UrlGeneratorInterface::ABSOLUTE_URL);
    }

}