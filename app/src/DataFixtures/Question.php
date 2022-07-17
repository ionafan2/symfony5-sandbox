<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Question extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        QuestionFactory::createMany(15);
        QuestionFactory::new()->unpublished()->createMany(5);


        $answer =new Answer();

        $answer->setContent("Test");
        $answer->setUsername("Test");

        $question = New \App\Entity\Question();
        $question->setQuestion('Some Test')
            ->setName('Name');

        $answer->setQuestion($question);

        $manager->persist($answer);
        $manager->persist($question);

        $manager->flush();
    }
}
