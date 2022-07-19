<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\QuestionTagFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(20);

        $questions = QuestionFactory::createMany(10);

        QuestionTagFactory::createMany(20, function () {
            return [
                'tag' => TagFactory::random(),
                'question' => QuestionFactory::random(),
            ];
        });

        QuestionFactory::new()
            ->unpublished()
            ->createMany(5);

        AnswerFactory::createMany(50, function () use ($questions) {
            return ['question' => $questions[array_rand($questions)]];
        });

        AnswerFactory::new(function () use ($questions) {
            return ['question' => $questions[array_rand($questions)]];
        })
            ->needsApproval()->many(10)->create();

        UserFactory::createOne(['email'=> 'test@test.com']);
        UserFactory::createOne([
            'email'=> 'admin@test.com',
            'roles' => ['ROLE_ADMIN']
        ]);

        UserFactory::createMany(10);

        $manager->flush();

    }
}
