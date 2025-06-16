<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/conditions-utilisation', name: 'app_conditions')]
    public function conditions(): Response
    {
        return $this->render('legal/conditions.html.twig');
    }

    #[Route('/politique-confidentialite', name: 'app_confidentialite')]
    public function confidentialite(): Response
    {
        return $this->render('legal/confidentialite.html.twig');
    }
}
