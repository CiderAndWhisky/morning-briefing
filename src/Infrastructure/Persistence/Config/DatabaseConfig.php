<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Config;

class DatabaseConfig
{
    public function __construct(
        private readonly string $host,
        private readonly int $port,
        private readonly string $name,
        private readonly string $user,
        private readonly string $password,
    ) {
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDsn(): string
    {
        return sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s',
            $this->host,
            $this->port,
            $this->name,
            $this->user,
            $this->password
        );
    }
}