<?php

declare(strict_types=1);

namespace App\Tests\Factories;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFactory
{
    public static function create(array $options, ?EntityManagerInterface $entityManager = null): User
    {
        $options = self::configureOptions($options);

        $user = new User();
        $user->setEmail($options['email']);
        $user->setPesel($options['pesel']);
        $user->setFirstName($options['firstName']);
        $user->setLastName($options['lastName']);

        self::persist($user, $entityManager);
        return $user;
    }

    protected static function configureOptions(array $options): array
    {
        $optionsResolver = new OptionsResolver();
        $faker = \Faker\Factory::create();

        $optionsResolver
            ->setDefault('email', function (Options $options) use ($faker): string {
                return $faker->email;
            })
            ->setDefault('pesel', '90080517455')
            ->setDefault('firstName', function (Options $options) use ($faker): string {
                return $faker->firstName;
            })
            ->setDefault('lastName', function (Options $options) use ($faker): string {
                return $faker->lastName;
            })
        ;

        return $optionsResolver->resolve($options);
    }

    private static function persist(User $user, ?EntityManagerInterface $entityManager): void
    {
        if ($entityManager INSTANCEOF EntityManagerInterface) {
            $entityManager->persist($user);
            $entityManager->flush();
        }
    }
}