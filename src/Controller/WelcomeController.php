<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class WelcomeController extends AbstractController
{
    #[Route('/', name: 'welcome')]
    public function index(): Response
    {
        return $this->render('welcome.html.twig');
    }
}
