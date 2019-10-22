<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTestCase extends WebTestCase
{
    /** @var EntityManager */
    protected $em;

    /** @var Client */
    protected $client;

    /** @var Connection */
    protected $connection;

    public function bootServices(): void
    {
        $this->client = self::createClient();
        $this->client->disableReboot();

        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
        $this->beginTransaction();
    }

    private function beginTransaction(): void
    {
        $this->connection = $this->em->getConnection();
        $this->connection->setAutoCommit(false);
        $this->connection->beginTransaction();
    }

    /**
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function tearDown(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->rollBack();
        }
        $this->em->close();

        parent::tearDown();

        foreach ($this as $property => $val) {
            unset($this->$property);
        }

        gc_collect_cycles();
    }

    public function assertStatusCode(int $statusCode): void
    {
        self::assertSame($statusCode, $this->client->getResponse()->getStatusCode());
    }

    public function assertSentEmails(int $count): void
    {
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');
        self::assertSame($count, $mailCollector->getMessageCount());
    }
}