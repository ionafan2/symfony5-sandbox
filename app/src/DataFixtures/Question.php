<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Question extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $question = new \App\Entity\Question();
        $question->setName('Missing pants')
            ->setSlug('missing-pants-' . rand(0, 1000))
            ->setVotes(rand(-10, 10))
            ->setQuestion(question: <<<EOF
Lorem ipsum dolor sit amet, consectetur adipisicing elit.
Accusantium aliquam, at consectetur cupiditate ea exercitationem facilis iusto laudantium maxime minima nisi placeat quasi,
 quibusdam repudiandae tempora tenetur veniam vero. Delectus.
EOF
            );

        if (rand(1, 10) > 2) {
            $question->setAskedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 10))));
        }

        $manager->persist($question);

        $manager->flush();
    }
}
