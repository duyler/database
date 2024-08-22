<?php

declare(strict_types=1);

namespace Duyler\Database;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Duyler\ActionBus\Build\Action;
use Duyler\ActionBus\Build\Event;
use Duyler\ActionBus\Build\SharedService;
use Duyler\Database\Action\FixturesAction;
use Duyler\Database\Action\MigrationStartAction;
use Duyler\Database\Provider\ConfigurationProvider;
use Duyler\Database\Provider\ConnectionProvider;
use Duyler\Database\Provider\EntityManagerProvider;
use Duyler\DependencyInjection\ContainerInterface;
use Duyler\Builder\Loader\LoaderServiceInterface;
use Duyler\Builder\Loader\PackageLoaderInterface;

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
            new SharedService(
                class: Connection::class,
                service: $connection,
                providers: [
                    Configuration::class => ConfigurationProvider::class,
                    Connection::class => ConnectionProvider::class,
                ],
            ),
        );

        $loaderService->addSharedService(
            new SharedService(
                class: EntityManager::class,
                service: $entityManager,
                providers: [
                    Configuration::class => ConfigurationProvider::class,
                    Connection::class => ConnectionProvider::class,
                    EntityManagerInterface::class => EntityManagerProvider::class,
                ],
            ),
        );

        $loaderService->addEvent(
            new Event(
                id: 'Migration.Start',
            ),
        );

        $loaderService->addAction(
            new Action(
                id: "DatabaseMigrationStart",
                handler: MigrationStartAction::class,
                listen: 'Migration.Start',
            ),
        );

        $loaderService->addEvent(
            new Event(
                id: 'Fixture.Start',
            ),
        );

        $loaderService->addAction(
            new Action(
                id: "DatabaseFixtureStart",
                handler: FixturesAction::class,
                listen: 'Fixture.Start',
            ),
        );
    }
}
