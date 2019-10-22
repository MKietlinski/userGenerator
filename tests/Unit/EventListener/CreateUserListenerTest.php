<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventListener;

use App\Entity\User;
use App\EventListener\CreateUserListener;
use App\Service\Mailer;
use App\Service\Mails\CreateUserMail;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Prophecy\Argument;

class CreateUserListenerTest extends TestCase
{
    private $listener;
    private $user;
    private $eventArgsMock;
    private $mailerMock;

    public function setUp(): void
    {
        $this->mailerMock = $this->prophesize(Mailer::class);
        $this->listener = new CreateUserListener($this->mailerMock->reveal());
        $this->user = new User();
        $this->eventArgsMock = $this->createMock(LifecycleEventArgs::class);
        $this->eventArgsMock->method('getObject')->willReturn($this->user);
    }

    /**
     * @dataProvider data_provider_for_test_should_set_user_active_and_send_welcome_email_if_is_adult
     */
    public function test_should_set_user_active_and_send_welcome_email_if_is_adult(string $pesel, bool $shouldExecuteListener): void
    {
        $this->user->setEmail('user@email.com');
        $this->user->setPesel($pesel);

        $this->listener->postPersist($this->eventArgsMock);

        $mailsToSend = $shouldExecuteListener ? 1 : 0;
        $this->mailerMock->send(new CreateUserMail('user@email.com'))->shouldBeCalledTimes($mailsToSend);

        $this->assertSame($shouldExecuteListener, $this->user->isActive());
    }

    public function data_provider_for_test_should_set_user_active_and_send_welcome_email_if_is_adult(): array
    {
        return [
          ['90080517455', true],
          ['05211320114', false],
          ['', false],
        ];
    }
}