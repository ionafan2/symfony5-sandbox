<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/auth/login', name: 'app_auth_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('/auth/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }
}
