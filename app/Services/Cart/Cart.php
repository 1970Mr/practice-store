<?php

namespace App\Services\Cart;

use App\Exceptions\QuantityExceededException;
use App\Models\Product;
use App\Services\Storage\Contracts\StorageInterface;
use Illuminate\Database\Eloquent\Collection;

readonly class Cart
{
    public function __construct(private StorageInterface $storage)
    {
    }

    /**
     * @throws QuantityExceededException
     */
    public function add(Product $product, int $quantity = 1): void
    {
        if ($this->has($product)) {
            $quantity = $this->get($product)['quantity'] + $quantity;
        }
        $this->set($product, $quantity);
    }

    /**
     * @throws QuantityExceededException
     */
    public function set(Product $product, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->delete($product->id);
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
        $total = 0;
        foreach ($this->all() as $item) {
            $total += $item->price * $item->quantity;
        }
        return $total;
    }

    public function total($additionalAmount): int
    {
        return $this->subtotal() + $additionalAmount;
    }

    public function itemCount(): int
    {
        return $this->storage->count();
    }

    public function has(Product $product): bool
    {
        return $this->storage->exists($product->id);
    }

    public function delete(Product $product): void
    {
        $this->storage->unset($product->id);
    }

    public function clear(): void
    {
        $this->storage->clear();
    }
}
