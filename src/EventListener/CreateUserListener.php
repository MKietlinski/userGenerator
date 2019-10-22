<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use App\Service\Mailer;
use App\Service\Mails\CreateUserMail;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CreateUserListener
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $user = $args->getObject();

        if (!$user instanceof User) {
            return;
        }

        if ($user->isAdult()) {
            $user->setActive(true);
            $this->mailer->send(new CreateUserMail($user->getEmail()));
        }
    }
}