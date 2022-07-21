<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
class QuestionController extends AbstractController
{
    #[Route(path: "/questions/{page<\d+>}", name: "app_question_list")]
    public function list(QuestionRepository $repository, int $page = 1): Response
    {
        $queryBuilder = $repository->createAskedOrderedByNewestQueryBuilder();

        $pagerfanta = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pagerfanta->setMaxPerPage(2);
        $pagerfanta->setCurrentPage($page);

        return $this->render('question/list.html.twig', [
            'pager' => $pagerfanta,
        ]);
    }

    #[Route(path: "/questions/new", name: "app_question_new")]
    #[IsGranted("ROLE_USER")]
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

    #[Route(path: "/questions/edit/{slug}", name: "app_question_edit")]
    public function edit(Question $question): Response
    {

        return $this->render('question/edit.html.twig', [
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
