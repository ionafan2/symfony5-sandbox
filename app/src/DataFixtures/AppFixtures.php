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
        UserFactory::createOne([
            'email'=> 'test@test.com'
        ]);
        UserFactory::createOne([
            'email'=> 'admin@test.com',
            'roles' => ['ROLE_ADMIN']
        ]);

        UserFactory::createMany(10);

        TagFactory::createMany(40);

        $questions = QuestionFactory::createMany(20, function () {
            return [
                'owner' => UserFactory::random(),
            ];
        });

        QuestionTagFactory::createMany(40, function () {
            return [
                'tag' => TagFactory::random(),
                'question' => QuestionFactory::random(),
            ];
        });

        QuestionFactory::new()
            ->unpublished()
            ->createMany(5, function () {
                return [
                    'owner' => UserFactory::random(),
                ];
            });

        AnswerFactory::createMany(50, function () use ($questions) {
            return ['question' => $questions[array_rand($questions)]];
        });

        AnswerFactory::new(function () use ($questions) {
            return ['question' => $questions[array_rand($questions)]];
        })
            ->needsApproval()->many(10)->create();

    }
}
