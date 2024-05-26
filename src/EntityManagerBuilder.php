<?php

declare(strict_types=1);

namespace Duyler\Database;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Duyler\DependencyInjection\Attribute\Finalize;

#[Finalize]
final class EntityManagerBuilder
{
    private ?EntityManagerInterface $entityManager = null;

    public function __construct(private ConnectionConfigInterface $connectionConfig) {}

    public function build(): EntityManagerInterface
    {
        $dbParams = [
            'driver' => $this->connectionConfig->getDriver(),
            'host' => $this->connectionConfig->getHost(),
            'port' => $this->connectionConfig->getPort(),
            'dbname' => $this->connectionConfig->getDatabase(),
            'user' => $this->connectionConfig->getUsername(),
            'password' => $this->connectionConfig->getPassword(),
            'charset' => $this->connectionConfig->getCharset(),
        ];

        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: $this->connectionConfig->getEntityPaths(),
            isDevMode: $this->connectionConfig->isDevMode(),
        );

        /** @psalm-suppress ArgumentTypeCoercion */
        $connection = DriverManager::getConnection($dbParams, $config);

        $this->entityManager = new EntityManager($connection, $config);
        return $this->entityManager;
    }

    public function finalize(): void
    {
        $this->entityManager?->clear();
        $this->entityManager?->close();
        if ($this->connectionConfig->closeAfterFinalize()) {
            $this->entityManager?->getConnection()->close();
        }
    }
}
