<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends BaseController
{
    /**
     * @throws \Doctrine\ORM\Query\QueryException
     */
    #[Route(path: '/answers/popular', name: 'app_popular_answers')]
    public function popularAnswers(AnswerRepository $answerRepository, Request $request): Response
    {
        $answers = $answerRepository->findMostPopular(
            $request->query->get('q')
        );

        return $this->render('answer/popularAnswers.html.twig', [
            'answers' => $answers
        ]);

    }

    #[Route(path: '/answers/{id}/vote', name: 'app_answer_vote', methods: 'POST')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function answerVote(
        Answer $answer, LoggerInterface $logger, Request $request, EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $logger->info('{user} is voting on answer {answer}!', [
            'user' => $this->getUser()->getEmail(),
            'answer' => $answer->getId(),
        ]);

        $data = json_decode($request->getContent(), true);
        $direction = $data['direction'] ?? 'up';

        // use real logic here to save this to the database
        if ($direction === 'up') {
            $logger->info('Voting up!');
            $answer->setVotes($answer->getVotes() + 1);
            $currentVoteCount = rand(7, 100);
        } else {
            $logger->info('Voting down!');
            $answer->setVotes($answer->getVotes() - 1);
        }

        $entityManager->flush();

        return $this->json(['votes' => $answer->getVotes()]);
    }
}