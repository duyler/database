<?php

declare(strict_types=1);

namespace Duyler\Database\Provider;

use Doctrine\ORM\ORMSetup;
use Duyler\Database\DatabaseConfig;
use Duyler\Database\DatabaseConfigInterface;
use Duyler\DependencyInjection\ContainerService;
use Duyler\DependencyInjection\Provider\AbstractProvider;

class ConfigurationProvider extends AbstractProvider
{
    public function bind(): array
    {
        return [
            DatabaseConfigInterface::class => DatabaseConfig::class,
        ];
    }

    public function factory(ContainerService $containerService): ?object
    {
        /** @var DatabaseConfigInterface $connectionConfig */
        $connectionConfig = $containerService->getInstance(DatabaseConfigInterface::class);

        return ORMSetup::createAttributeMetadataConfiguration(
            paths: $connectionConfig->getEntityPaths(),
            isDevMode: $connectionConfig->isDevMode(),
        );
    }
}
