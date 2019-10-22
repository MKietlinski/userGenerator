<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUsersWithProgrammingLanguages(array $languages)
    {
        if (empty($languages)) {
            return [];
        }

        $qb = $this->createQueryBuilder('user');

        foreach ($languages as $language) {
            $qb->orWhere($qb->expr()->like('user.programmingLanguages', "'%$language%'"));
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
}