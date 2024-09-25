<?php

declare(strict_types=1);

namespace Duyler\Database\Provider;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duyler\DI\ContainerService;
use Duyler\DI\Provider\AbstractProvider;

class EntityManagerProvider extends AbstractProvider
{
    public function getArguments(ContainerService $containerService): array
    {
        /** @var Connection $connection */
        $connection = $containerService->getInstance(Connection::class);

        /** @var Configuration $configuration */
        $configuration = $containerService->getInstance(Configuration::class);

        return [
            'conn' => $connection,
            'config' => $configuration,
            'eventManager' => null,
        ];
    }

    public function bind(): array
    {
        return [
            EntityManagerInterface::class => EntityManager::class,
        ];
    }

    public function finalizer(): callable
    {
        return function (EntityManagerInterface $entityManager) {
            $entityManager->clear();
        };
    }
}
