<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventListener;

use App\Entity\User;
use App\EventListener\CreateUserListener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class CreateUserListenerTest extends TestCase
{
    private $listener;
    private $user;
    private $eventArgs;

    public function setUp(): void
    {
        $this->listener = new CreateUserListener();
        $this->user = new User();
        $this->eventArgs = $this->createMock(LifecycleEventArgs::class);
        $this->eventArgs->method('getObject')->willReturn($this->user);
    }

    /**
     * @dataProvider data_provider_for_test_should_set_user_active_if_is_adult
     */
    public function test_should_set_user_active_if_is_adult(string $pesel, bool $shouldBeActive): void
    {
        $this->user->setPesel($pesel);

        $this->listener->postPersist($this->eventArgs);

        $this->assertSame($shouldBeActive, $this->user->isActive());
    }

    public function data_provider_for_test_should_set_user_active_if_is_adult(): array
    {
        return [
          ['90080517455', true],
          ['05211320114', false],
          ['', false],
        ];
    }
}