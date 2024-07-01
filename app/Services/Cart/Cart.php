<?php

namespace App\Services\Cart;

use App\Exceptions\QuantityExceededException;
use App\Models\Product;
use App\Services\Storage\Contracts\StorageInterface;
use Illuminate\Database\Eloquent\Collection;

class Cart
{
    public function __construct(private readonly StorageInterface $storage)
    {
    }

    /**
     * @throws QuantityExceededException
     */
    public function add(Product $product, int $quantity): void
    {
        if ($this->has($product)) {
            $quantity = $this->get($product)['quantity'] + $quantity;
        }
        $this->set($product, $quantity);
    }

    /**
     * @throws QuantityExceededException
     */
    private function set(Product $product, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->storage->unset($product->id);
            return;
        }

        if (!$product->hasStock($quantity)) {
            throw new QuantityExceededException();
        }

        $this->storage->set($product->id, ['quantity' => $quantity]);
    }

    public function get(Product $product): mixed
    {
        return $this->storage->get($product->id);
    }

    public function all(): Collection
    {
        $products = Product::query()->find(array_keys($this->storage->all()));
        foreach ($products as $product) {
            $product->quantity = $this->get($product)['quantity'];
        }
        return $products;
    }

    public function subtotal(): int
    {
        $subtotal = 0;
        foreach ($this->all() as $item) {
            $subtotal += $item->price * $item->quantity;
        }
        return $subtotal;
    }

    public function itemsCount(): int
    {
        return $this->storage->count();
    }

    public function has(Product $product): bool
    {
        return $this->storage->exists($product->id);
    }

    public function clear(): void
    {
        $this->storage->clear();
    }
}
