<?php

declare(strict_types=1);

namespace Duyler\Database;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duyler\Database\Provider\ConfigurationProvider;
use Duyler\Database\Provider\ConnectionProvider;
use Duyler\Database\Provider\EntityManagerProvider;
use Duyler\DependencyInjection\ContainerInterface;
use Duyler\Framework\Loader\LoaderServiceInterface;
use Duyler\Framework\Loader\PackageLoaderInterface;

class Loader implements PackageLoaderInterface
{
    public function __construct(private ContainerInterface $container) {}

    public function load(LoaderServiceInterface $loaderService): void
    {
        $this->container->addProviders([
            Configuration::class => ConfigurationProvider::class,
            Connection::class => ConnectionProvider::class,
            EntityManagerInterface::class => EntityManagerProvider::class,
        ]);

        /** @var Connection $connection */
        $connection = $this->container->get(Connection::class);

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get(EntityManagerInterface::class);

        $this->container->set($connection);

        $loaderService->addSharedService(
            $entityManager,
            [
                EntityManagerInterface::class => EntityManager::class,
            ],
        );
        $loaderService->addSharedService($connection);
    }
}
