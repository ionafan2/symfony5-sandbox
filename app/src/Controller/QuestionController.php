<?php

namespace App\Controller;

use App\Entity\Question;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('question/homepage.html.twig');
    }

    /**
     * @Route("/questions/new", name="app_new")
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function new(EntityManagerInterface $em)
    {

        $question = new Question();
        $question->setName('Missing pants')
            ->setSlug('missing-pants-' . rand(0, 1000))
            ->setQuestion(question: <<<EOF
Lorem ipsum dolor sit amet, consectetur adipisicing elit.
Accusantium aliquam, at consectetur cupiditate ea exercitationem facilis iusto laudantium maxime minima nisi placeat quasi,
 quibusdam repudiandae tempora tenetur veniam vero. Delectus.
EOF
            );

        if (rand(1, 10) > 2) {
            $question->setAskedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 10))));
        }

        $em->persist($question);
        $em->flush();

        return new Response(sprintf("ID: %d and Slug: %s",
            $question->getId(), $question->getSlug()
        ));
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show($slug, MarkdownHelper $markdownHelper, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Question::class);

        /** @var Question|null $question */
        $question = $repository->findOneBy(['slug' => $slug]);

        if (null === $question) {
            throw $this->createNotFoundException();
        }

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
}
