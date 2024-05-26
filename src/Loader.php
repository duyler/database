<?php

declare(strict_types=1);

namespace Duyler\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duyler\DependencyInjection\ContainerInterface;
use Duyler\Framework\Loader\LoaderServiceInterface;
use Duyler\Framework\Loader\PackageLoaderInterface;

class Loader implements PackageLoaderInterface
{
    public function __construct(private ContainerInterface $container) {}

    public function load(LoaderServiceInterface $loaderService): void
    {
        $this->container->bind([
            ConnectionConfigInterface::class => ConnectionConfig::class,
            EntityManagerInterface::class => EntityManager::class,
        ]);

        /** @var EntityManagerBuilder $builder */
        $builder = $this->container->get(EntityManagerBuilder::class);
        $entityManager = $builder->build();
        $connection = $entityManager->getConnection();

        $this->container->set($entityManager);
        $this->container->set($connection);

        $loaderService->addSharedService($entityManager, [EntityManagerInterface::class => EntityManager::class]);
        $loaderService->addSharedService($connection);
    }
}
