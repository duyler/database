<?php

declare(strict_types=1);

namespace Duyler\Database\Provider;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Duyler\Database\DatabaseConfigInterface;
use Duyler\DependencyInjection\ContainerService;
use Duyler\DependencyInjection\Provider\AbstractProvider;

/**
 * @psalm-suppress ArgumentTypeCoercion
 */
class ConnectionProvider extends AbstractProvider
{
    public function factory(ContainerService $containerService): ?object
    {
        /** @var DatabaseConfigInterface $connectionConfig */
        $connectionConfig = $containerService->getInstance(DatabaseConfigInterface::class);

        /** @var Configuration $configuration */
        $configuration = $containerService->getInstance(Configuration::class);

        $dbParams = [
            'driver' => $connectionConfig->getDriver(),
            'host' => $connectionConfig->getHost(),
            'port' => $connectionConfig->getPort(),
            'dbname' => $connectionConfig->getDatabase(),
            'user' => $connectionConfig->getUsername(),
            'password' => $connectionConfig->getPassword(),
            'charset' => $connectionConfig->getCharset(),
        ];

        return DriverManager::getConnection($dbParams, $configuration);
    }
}
