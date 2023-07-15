<?php

namespace Assegai\Collections;

use ArrayIterator;
use Assegai\Collections\Interfaces\CollectionInterface;
use Traversable;

/**
 * The base class for all collections.
 */
class AbstractCollection implements CollectionInterface
{
  /**
   * Constructs a new Collection instance.
   *
   * @template T
   * @param class-string<T> $type The type of the collection.
   * @var array $items The items in the collection.
   */
  public function __construct(
    protected string $type,
    protected array $items = []
  )
  {}

  /**
   * @inheritDoc
   * @template T
   * @return array<T> The items in the collection.
   */
  public function toArray(): array
  {
    return $this->items;
  }

  /**
   * @inheritDoc
   */
  public function clear(): void
  {
    $this->items = [];
  }

  /**
   * @inheritDoc
   *
   * @template T
   * @param T $item The item to check for.
   * @return bool True if the collection contains the item; otherwise, false.
   */
  public function contains(mixed $item): bool
  {
    return in_array($item, $this->items, true);
  }

  /**
   * @inheritDoc
   */
  public function isEmpty(): bool
  {
    return empty($this->items);
  }

  /**
   * Determines whether the collection is not empty.
   *
   * @return bool True if the collection is not empty; otherwise, false.
   */
  public function isNotEmpty(): bool
  {
    return !$this->isEmpty();
  }

  /**
   * @inheritDoc
   */
  public function compareTo(mixed $other): int
  {
    return $this->toArray() <=> $other->toArray();
  }

  /**
   * @inheritDoc
   */
  public function getIterator(): Traversable
  {
    return new ArrayIterator($this->items);
  }

  /**
   * @inheritDoc
   */
  public function serialize(): string
  {
    return json_encode($this->__serialize());
  }

  /**
   * @inheritDoc
   */
  public function unserialize(string $data): void
  {
    $this->__unserialize(json_decode($data, true));
  }

  /**
   * @inheritDoc
   */
  public function count(): int
  {
    return count($this->items);
  }

  /**
   * Filters the collection using a callback.
   *
   * @param callable $callback
   * @return $this
   */
  public function filter(callable $callback): static
  {
    return new static($this->type, array_filter($this->items, $callback));
  }

  /**
   * Maps the collection using a callback.
   *
   * @param callable $callback
   * @return $this
   */
  public function map(callable $callback): static
  {
    return new static($this->type, array_map($callback, $this->items));
  }

  /**
   * Reduces the collection to a single value using a callback.
   *
   * @template T
   * @param callable $callback The callback function.
   * @param T $initial
   * @return T The reduced value.
   */
  public function reduce(callable $callback, mixed $initial = null): mixed
  {
    return array_reduce($this->items, $callback, $initial);
  }

  /**
   * @inheritDoc
   */
  public function __toString(): string
  {
    return var_export($this->items, true);
  }

  /**
   * @inheritDoc
   * @template T
   * @param T $other The item to compare to.
   */
  public function equals(mixed $other): bool
  {
    return $this->compareTo($other) === 0;
  }

  /**
   * @inheritDoc
   */
  public function jsonSerialize(): array
  {
    return $this->items;
  }

  /**
   * Magic method for serializing the collection.
   *
   * @template T
   * @return array<T> The items in the collection.
   */
  public function __serialize(): array
  {
    return $this->items;
  }

  /**
   * Magic method for deserializing the collection.
   *
   * @template T
   * @param array<T> $data The items in the collection.
   * @return void
   */
  public function __unserialize(array $data): void
  {
    $this->items = $data;
  }

  /**
   * Gets the type error message for the specified method.
   *
   * @param string $methodName The name of the method.
   * @param string $typeName The name of the type.
   * @param int $argIndex The index of the argument.
   * @return string The type error message.
   */
  protected function getTypeErrorMessage(string $methodName, string $typeName, int $argIndex = 1): string
  {
    return get_method_arg_type_error_message($methodName, $argIndex, $this->type, $typeName);
  }
}