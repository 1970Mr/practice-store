<?php

namespace App\Services\Storage\Session;

use App\Services\Storage\Contracts\StorageInterface;
use Illuminate\Support\Facades\Session;

class SessionStorage implements StorageInterface
{
    public function __construct(private readonly string $bucket = 'default')
    {
    }

    public function get(int $key): mixed
    {
        return Session::get($this->bucket . '.' . $key);
    }

    public function set(int $key, mixed $value): void
    {
        Session::put($this->bucket . '.' . $key, $value);
    }

    public function all(): array
    {
        return Session::get($this->bucket) ?? [];
    }

    public function exists(int $key): bool
    {
        return Session::exists($this->bucket . '.' . $key);
    }

    public function unset(int $key): void
    {
        Session::forget($key);
    }

    public function clear(): void
    {
        Session::forget($this->bucket);
    }

    public function count(): int
    {
        return count($this->all());
    }
}
