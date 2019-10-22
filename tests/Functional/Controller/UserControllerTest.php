<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use App\Tests\AppTestCase;
use App\Tests\Factories\UserFactory;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends AppTestCase
{
    public function setUp(): void
    {
        $this->bootServices();
    }

    public function test_should_create_user_on_valid_data(): void
    {
        $data = [
            'email' => 'user@gmail.com',
            'pesel' => '90080517455',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'programmingLanguages' => []
        ];

        $this->client->request(Request::METHOD_POST, '/', ['user' => $data]);
        $userRepository = $this->em->getRepository(User::class);

        $this->assertStatusCode(200);
        $this->assertCount(1, $userRepository->findAll());
    }

    public function test_should_not_create_user_when_email_is_occupied(): void
    {
        UserFactory::create(['email' => 'user@gmail.com'], $this->em);

        $data = [
            'email' => 'user@gmail.com',
            'pesel' => '90080517455',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'programmingLanguages' => []
        ];

        $this->client->request(Request::METHOD_POST, '/', ['user' => $data]);
        $userRepository = $this->em->getRepository(User::class);

        $this->assertStatusCode(200);
        $this->assertCount(1, $userRepository->findAll());
    }

    public function test_should_not_create_user_with_invalid_pesel(): void
    {
        $data = [
            'email' => 'user@gmail.com',
            'pesel' => '12345678901',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'programmingLanguages' => []
        ];

        $this->client->request(Request::METHOD_POST, '/', ['user' => $data]);
        $userRepository = $this->em->getRepository(User::class);

        $this->assertStatusCode(200);
        $this->assertCount(0, $userRepository->findAll());
    }

    public function test_should_set_user_active_when_is_adult_and_send_welcome_email(): void
    {
        $data = [
            'email' => 'user@gmail.com',
            'pesel' => '90080517455',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'programmingLanguages' => []
        ];

        $this->client->enableProfiler();
        $this->client->request(Request::METHOD_POST, '/', ['user' => $data]);
        $user = $this->em->getRepository(User::class)->findAll()[0];

        $this->assertStatusCode(200);
        $this->assertTrue($user->isActive());
        $this->assertSentEmails(1);
    }
}