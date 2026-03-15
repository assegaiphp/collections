<?php

namespace Assegai\Collections;

use ArrayIterator;
use Assegai\Collections\Interfaces\CollectionInterface;
use TypeError;
use Traversable;
use UnexpectedValueException;

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
   * Determines whether the collection does not contain an item.
   *
   * @template T
   * @param T $item The item to check for.
   * @return bool True if the collection does not contain the item; otherwise, false.
   */
  public function doesNotContain(mixed $item): bool
  {
    return !$this->contains($item);
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
    if (!$other instanceof CollectionInterface)
    {
      throw new TypeError(
        get_method_arg_type_error_message(
          __METHOD__,
          1,
          CollectionInterface::class,
          $this->resolveTypeName($other),
        )
      );
    }

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
    $serialized = json_encode($this->__serialize());

    if ($serialized === false)
    {
      throw new UnexpectedValueException('Failed to serialize collection.');
    }

    return $serialized;
  }

  /**
   * @inheritDoc
   */
  public function unserialize(string $data): void
  {
    $decoded = json_decode($data, true);

    if (!is_array($decoded))
    {
      throw new UnexpectedValueException('Invalid serialized collection payload.');
    }

    $this->__unserialize($decoded);
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
    if (!$other instanceof CollectionInterface)
    {
      return false;
    }

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
    return [
      'type' => $this->type,
      'items' => $this->items,
    ];
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
    if (array_key_exists('type', $data) && array_key_exists('items', $data))
    {
      if (!is_string($data['type']) || !is_array($data['items']))
      {
        throw new UnexpectedValueException('Invalid serialized collection payload.');
      }

      $this->type = $data['type'];
      $this->hydrateItems($data['items']);
      return;
    }

    if (isset($this->type) && array_is_list($data))
    {
      $this->hydrateItems($data);
      return;
    }

    throw new UnexpectedValueException('Invalid serialized collection payload.');
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

  protected function assertItemType(mixed $item, string $methodName, int $argIndex = 1): void
  {
    $typeName = $this->resolveTypeName($item);

    if ($typeName !== $this->type && is_subclass_of($item, $this->type) === false)
    {
      throw new TypeError($this->getTypeErrorMessage($methodName, $typeName, $argIndex));
    }
  }

  protected function resolveTypeName(mixed $value): string
  {
    return match (true) {
      is_object($value) => get_class($value),
      default => gettype($value),
    };
  }

  /**
   * Rebuilds the collection contents while preserving each concrete collection's invariants.
   *
   * @param array $items
   * @return void
   */
  protected function hydrateItems(array $items): void
  {
    $this->items = [];

    foreach (array_values($items) as $item)
    {
      if (method_exists($this, 'add'))
      {
        $this->add($item);
        continue;
      }

      if (method_exists($this, 'enqueue'))
      {
        $this->enqueue($item);
        continue;
      }

      if (method_exists($this, 'push'))
      {
        $this->push($item);
        continue;
      }

      $this->items[] = $item;
    }
  }
}
