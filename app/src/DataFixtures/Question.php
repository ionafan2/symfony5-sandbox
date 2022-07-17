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
        QuestionFactory::createMany(15);
        QuestionFactory::new()->unpublished()->createMany(5);

        AnswerFactory::createMany(100);

    }
}
