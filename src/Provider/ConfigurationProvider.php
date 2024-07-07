<?php

declare(strict_types=1);

namespace Duyler\Database\Provider;

use Doctrine\ORM\ORMSetup;
use Duyler\Database\ConnectionConfig;
use Duyler\Database\ConnectionConfigInterface;
use Duyler\DependencyInjection\ContainerService;
use Duyler\DependencyInjection\Provider\AbstractProvider;

class ConfigurationProvider extends AbstractProvider
{
    public function bind(): array
    {
        return [
            ConnectionConfigInterface::class => ConnectionConfig::class,
        ];
    }

    public function factory(ContainerService $containerService): ?object
    {
        /** @var ConnectionConfigInterface $connectConfig */
        $connectionConfig = $containerService->getInstance(ConnectionConfigInterface::class);

        return ORMSetup::createAttributeMetadataConfiguration(
            paths: $connectionConfig->getEntityPaths(),
            isDevMode: $connectionConfig->isDevMode(),
        );
    }
}
