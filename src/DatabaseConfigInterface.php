<?php

declare(strict_types=1);

namespace Duyler\Database;

interface DatabaseConfigInterface
{
    /** @return string[] */
    public function getEntityPaths(): array;

    public function isDevMode(): bool;

    public function getDriver(): string;

    public function getHost(): string;

    public function getPort(): int;

    public function getDatabase(): string;

    public function getUsername(): string;

    public function getPassword(): string;

    public function getCharset(): string;

    /** @return array<string, string> */
    public function getMigrationsPaths(): array;

    public function getFixturesPaths(): string;
}
