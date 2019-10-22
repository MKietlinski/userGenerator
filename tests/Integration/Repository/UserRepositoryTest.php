<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\User;
use App\Tests\AppTestCase;
use App\Tests\Factories\UserFactory;

class UserRepositoryTest extends AppTestCase
{
    private $userRepository;

    public function setUp(): void
    {
        $this->bootServices();
        $this->userRepository = $this->em->getRepository(User::class);
    }

    /**
     * @dataProvider data_provider_for_test_should_return_users_with_specific_programming_languages
     */
    public function test_should_return_users_with_specific_programming_languages(array $languages, int $shouldReturnCount): void
    {
        UserFactory::create(['programmingLanguages' => ['php']], $this->em);
        UserFactory::create(['programmingLanguages' => ['java']], $this->em);

        $result = $this->userRepository->findUsersWithProgrammingLanguages($languages);
        $this->assertCount($shouldReturnCount, $result);
    }

    public function data_provider_for_test_should_return_users_with_specific_programming_languages(): array
    {
        return [
          [['php', 'java'], 2],
          [['php', 'ruby'], 1],
          [['php'], 1],
          [['c'], 0],
          [[], 0],
        ];
    }
}