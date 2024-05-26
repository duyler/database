<?php

declare(strict_types=1);

namespace Duyler\Database;

class ConnectionConfig implements ConnectionConfigInterface
{
    public function __construct(
        private string $driver,
        private string $host,
        private string $username,
        private string $password,
        private string $database,
        private int $port,
        private string $charset = 'utf-8',
        /** @var string[] */
        private array $entityPaths = [],
        private bool $isDevMode = false,
        private bool $closeAfterFinalize = false,
    ) {}

    public function getEntityPaths(): array
    {
        return $this->entityPaths;
    }

    public function isDevMode(): bool
    {
        return $this->isDevMode;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function closeAfterFinalize(): bool
    {
        return $this->closeAfterFinalize;
    }
}
