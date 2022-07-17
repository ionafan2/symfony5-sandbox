<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Question extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $questions = QuestionFactory::createMany(15);
        QuestionFactory::new()->unpublished()->createMany(5);

        AnswerFactory::createMany(100, function () use($questions) {
            return ['question' => $questions[array_rand($questions)]];
        });

    }
}
