<?php

declare(strict_types=1);

namespace App\Service\Mails;

interface MailInterface
{
    public function getSubject(): string;
    public function getTo(): string;
    public function getTemplatePath(): string;
    public function getParameters(): array;
}