<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger,
        private bool            $isDebug
    )
    {
    }

    #[Route(path: "/questions", name: "app_question_list")]
    public function homepage(QuestionRepository $repository)
    {
        $questions = $repository->findAllAskedOrderByNewest();

        return $this->render('question/homepage.html.twig', [
            'questions' => $questions,
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
        if (!$this->isDebug) {
            $this->logger->info(__FUNCTION__);
        }

        $answers = [
            'Make sure your cat is sitting *purrrfectly* still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers
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
