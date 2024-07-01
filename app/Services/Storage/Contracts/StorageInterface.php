<?php

namespace App\Services\Storage\Contracts;

interface StorageInterface
{
    public function get(int $key): mixed;
    public function set(int $key, mixed $value): void;
    public function all(): array;
    public function exists(int $key): bool;
    public function unset(int $key): void;
    public function clear(): void;
    public function count(): int;
}
