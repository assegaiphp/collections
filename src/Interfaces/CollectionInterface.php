<?php

namespace Assegai\Collections\Interfaces;

use Countable;
use IteratorAggregate;
use JsonSerializable;
use Serializable;
use Stringable;

/**
 * Represents a collection of items. This interface is implemented by all collections.
 *
 * @template T
 */
interface CollectionInterface extends Countable, IteratorAggregate, JsonSerializable, Serializable, Stringable, ComparableInterface
{
  /**
   * Filters the collection using the given callback.
   *
   * @param callable $callback
   * @return $this
   */
  public function filter(callable $callback): static;

  /**
   * Maps the collection using the given callback.
   *
   * @param callable $callback
   * @return $this
   */
  public function map(callable $callback): static;

  /**
   * Reduces the collection using the given callback.
   *
   * @template T The type of the reduced value.
   * @param callable $callback The callback function.
   * @param T $initial The initial value.
   * @return T The reduced value.
   */
  public function reduce(callable $callback, mixed $initial = null): mixed;

  /**
   * Copies the items in the collection to an array.
   *
   * @template T
   * @return array<T> The items in the collection.
   */
  public function toArray(): array;

  /**
   * Removes all items from the collection.
   *
   * @return void
   */
  public function clear(): void;

  /**
   * Determines whether the collection contains a specific item.
   *
   * @template T
   * @param T $other The item to locate in the collection.
   * @return bool True if the item is found; otherwise, false.
   */
  public function contains(mixed $other): bool;
}