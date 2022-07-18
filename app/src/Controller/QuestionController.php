<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route(path: "/questions", name: "app_question_list")]
    public function homepage(QuestionRepository $repository)
    {
        $queryBuilder = $repository->createAskedOrderedByNewestQueryBuilder();

        return $this->render('question/homepage.html.twig', [
            'questions' => $queryBuilder->getQuery()->getResult(),
        ]);
    }

    #[Route(path: "/questions/new", name: "app_question_new")]
    public function new(EntityManagerInterface $em)
    {
        return new Response('For v2');
    }

    #[Route(path: "/questions/{slug}", name: "app_question_show")]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question
        ]);
    }

    #[Route(path: "/questions/{slug}/vote", name: "app_question_vote", methods: "POST")]
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager)
    {
        $direction = $request->request->get('direction');

        if ($direction === 'up') {
            $question->upVote();
        } elseif ($direction === 'down') {
            $question->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_question_show', [
            'slug' => $question->getSlug()
        ]);
    }
}
