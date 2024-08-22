<?php

declare(strict_types=1);

namespace Duyler\Database\Action;

use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\Tools\Console\Command;
use Duyler\Database\DatabaseConfigInterface;
use Symfony\Component\Console\Application;

class MigrationStartAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DatabaseConfigInterface $config,
    ) {}

    public function __invoke()
    {
        echo "Starting Migration\n";

        $config = [
            'table_storage' => [
                'table_name' => 'doctrine_migration_versions',
                'version_column_name' => 'version',
                'version_column_length' => 191,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],

            'migrations_paths' => [
                'Migrations' => 'migrations',
            ],

            'all_or_nothing' => true,
            'transactional' => true,
            'check_database_platform' => true,
            'organize_migrations' => 'none',
            'connection' => null,
            'em' => null,
        ];

        $dependencyFactory = DependencyFactory::fromConnection(
            new ConfigurationArray($config),
            new ExistingConnection($this->entityManager->getConnection()),
        );

        $cli = new Application('Doctrine Migrations');
        $cli->setCatchExceptions(true);

        $cli->addCommands([
            new Command\DumpSchemaCommand($dependencyFactory),
            new Command\ExecuteCommand($dependencyFactory),
            new Command\GenerateCommand($dependencyFactory),
            new Command\LatestCommand($dependencyFactory),
            new Command\ListCommand($dependencyFactory),
            new Command\MigrateCommand($dependencyFactory),
            new Command\RollupCommand($dependencyFactory),
            new Command\StatusCommand($dependencyFactory),
            new Command\SyncMetadataCommand($dependencyFactory),
            new Command\VersionCommand($dependencyFactory),
        ]);

        $cli->run();
    }
}
