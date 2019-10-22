<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Mails\MailInterface;

class Mailer
{
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(MailInterface $mail): void
    {
        $message = (new \Swift_Message($mail->getSubject()))
            ->setFrom('contact@userGenerator.com')
            ->setTo($mail->getTo())
            ->setBody($this->twig->render($mail->getTemplatePath(), $mail->getParameters()), 'text/html');

        $this->mailer->send($message);
    }
}