<?php

declare(strict_types=1);

namespace Duyler\Database\Provider;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Duyler\Database\ConnectionConfigInterface;
use Duyler\DependencyInjection\ContainerService;
use Duyler\DependencyInjection\Provider\AbstractProvider;

/**
 * @psalm-suppress ArgumentTypeCoercion
 */
class ConnectionProvider extends AbstractProvider
{
    public function factory(ContainerService $containerService): ?object
    {
        /** @var ConnectionConfigInterface $connectionConfig */
        $connectionConfig = $containerService->getInstance(ConnectionConfigInterface::class);

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
