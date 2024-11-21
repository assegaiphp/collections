<?php

namespace Assegai\Collections\Interfaces;

/**
 * Represents a strongly typed set of items that can be accessed by index. Provides methods to search, sort, and
 * manipulate sets.
 *
 * @template T
 */
interface SetInterface extends CollectionInterface
{
  /**
   * Adds an item to the set.
   *
   * @template T The type of the item.
   * @param T $item The item to add.
   * @return void
   */
  public function add(mixed $item): void;

  /**
   * Adds all items in the specified collection to the current set.
   *
   * @param mixed ...$items The collection of items to add to the set.
   * @return void
   */
  public function addAll(mixed ...$items): void;

  /**
   * Removes an item from the set.
   *
   * @template T The type of the item.
   * @param T $item The item to remove.
   * @return void
   */
  public function remove(mixed $item): void;

  /**
   * Removes all items in the specified collection from the current set.
   *
   * @param iterable $other The collection of items to remove from the set.
   * @return void
   */
  public function exceptWith(iterable $other): void;

  /**
   * Modifies the current set so that it contains only items that are also in a specified collection.
   *
   * @param iterable $other The collection to compare to the current set.
   * @return void
   */
  public function intersectWith(iterable $other): void;

  /**
   * Determines whether the current set is a proper (strict) subset of a specified collection.
   *
   * @param iterable $other The collection to compare to the current set.
   * @return bool true if the current set is a proper subset of other; otherwise, false.
   */
  public function isSubsetOf(iterable $other): bool;

  /**
   * Determines whether the current set overlaps with the specified collection.
   *
   * @param iterable $other The collection to compare to the current set.
   * @return bool true if the current set and other share at least one common item; otherwise, false.
   */
  public function overlaps(iterable $other): bool;

  /**
   * Modifies the current set so that it contains all elements that are present in the current set, in the specified
   * collection, or in both.
   *
   * @param iterable $other The collection to compare to the current set.
   * @return void
   */
  public function unionWith(iterable $other): void;
}