<?php

declare(strict_types=1);

namespace Duyler\Database;

interface ConnectionConfigInterface
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

    public function closeAfterFinalize(): bool;
}
