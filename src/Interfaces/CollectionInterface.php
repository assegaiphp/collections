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