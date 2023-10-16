<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\PublicationType;
use App\Service\FlashMessageHelper;
use App\Service\FlashMessageHelperInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\ExpressionLanguage\Expression;
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

class PremiumController extends AbstractController
{
    #[IsGranted(new Expression("is_granted('ROLE_USER') and !user.isPremium()"))]
    #[Route('/premium', name: 'premiumInfos', methods: ["GET"])]
    public function premiumInfos(): Response
    {
        return $this->render('premium/premium-infos.html.twig');
    }
}