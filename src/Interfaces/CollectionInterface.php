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
   * Returns a filtered collection using the given callback.
   *
   * @param callable $callback The callback function.
   * @return $this The filtered collection.
   */
  public function filter(callable $callback): static;

  /**
   * Applies the given callback to each item in the collection.
   *
   * @param callable $callback The callback function.
   * @return $this Returns a collection containing the results after applying the callback to each item.
   */
  public function map(callable $callback): static;

  /**
   * Iteratively reduces the collection to a single value using a callback function.
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