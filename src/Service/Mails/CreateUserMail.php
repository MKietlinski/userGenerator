<?php

declare(strict_types=1);

namespace App\Service\Mails;

class CreateUserMail implements MailInterface
{
    private $to;

    private $parameters = [];

    public function __construct(string $to)
    {
        $this->to = $to;
    }

    public function getSubject(): string
    {
        return 'Welcome email';
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getTemplatePath(): string
    {
        return 'Mails/createUser.html.twig';
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}