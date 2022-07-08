<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }

    #[Route('/questions/{slug}', name: 'app_question')]
    public function show($slug): Response
    {
        return $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
            'slug' => ucfirst(str_replace(search: "-", replace:" ", subject: $slug)),
        ]);
    }

}
