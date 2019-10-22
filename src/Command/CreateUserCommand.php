<?php

namespace App\Command;

use App\Form\UserType;
use App\Service\FormErrorsFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Form\FormFactoryInterface;

class CreateUserCommand extends Command
{
    private $formFactory;
    private $em;
    private $errors;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->formFactory = $formFactory;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setName('user:create')
            ->setAliases(['u:c'])
            ->setDescription('User generator')
            ->setHelp('Pass arguments to create user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $data = [];
        $data['email'] = $helper->ask($input, $output, new Question('Email: '));
        $data['pesel'] = $helper->ask($input, $output, new Question('Pesel: '));
        $data['firstName'] = $helper->ask($input, $output, new Question('Imie: '));
        $data['lastName'] = $helper->ask($input, $output, new Question('Nazwisko: '));
        $data['programmingLanguages'] = explode(',' ,$helper->ask($input, $output, new Question('Języki programowania (oddzielone przecinkiem): ')));

        try {
            $this->createUser($data);
        } catch (\InvalidArgumentException $exception) {
            foreach ($this->errors as $label => $error) {
                $io->error($error);
            }
        }
    }

    private function createUser($data)
    {
        $form = $this->formFactory->create(UserType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            $this->errors = FormErrorsFormatter::format($form);
            throw new \InvalidArgumentException();
        }

        $user = $form->getData();
        $this->em->persist($user);
        $this->em->flush();
    }
}