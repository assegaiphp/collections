<?php

namespace Assegai\Collections;

use ArrayAccess;
use OutOfBoundsException;
use TypeError;

/**
 * Represents a strongly typed list of items that can be accessed by index. Provides methods to search, sort, and
 * manipulate lists.
 *
 * @template T
 * @implements ArrayAccess<int, T>
 */
class ItemList extends AbstractCollection implements ArrayAccess
{
  private bool $sortedForBinarySearch = true;

  /**
   * Constructs a new ItemList instance.
   *
   * @param class-string<T> $type The type of the items in the list.
   * @param array $items The items in the list.
   */
  public function __construct(string $type, array $items = [])
  {
    parent::__construct($type);

    foreach ($items as $item) {
      $this->add($item);
    }
  }

  /**
   * Adds an item to the list.
   *
   * @param T $item The item to add.
   * @return void
   */
  public function add(mixed $item): void
  {
    $this->assertItemType($item, __METHOD__);
    $this->updateSortedStateBeforeAppend($item);
    $this->items[] = $item;
  }

  /**
   * Uses a binary search algorithm to locate a specific element in the list.
   *
   * @param T $item The item to search for.
   * @return int The index of the item if found; otherwise, -1.
   */
  public function binarySearch(mixed $item): int
  {
    if (!$this->sortedForBinarySearch)
    {
      return $this->findIndex(fn($candidate) => $candidate === $item);
    }

    $low = 0;
    $high = $this->count() - 1;

    while ($low <= $high) {
      $mid = (int)(($low + $high) / 2);
      $guess = $this->items[$mid];
      $comparison = $this->compareValues($guess, $item);

      if ($comparison === null)
      {
        return $this->findIndex(fn($candidate) => $candidate === $item);
      }

      if ($comparison === 0) {
        return $mid;
      }

      if ($comparison > 0) {
        $high = $mid - 1;
      } else {
        $low = $mid + 1;
      }
    }

    return -1;
  }

  /**
   * Determines whether the list contains elements that match the conditions defined by the specified predicate.
   *
   * @param callable $predicate The predicate function to use when determining whether an item matches the conditions.
   * @return bool True if the list contains one or more elements that match the conditions defined by the specified
   */
  public function exists(callable $predicate): bool
  {
    $item = $this->find($predicate);

    return $item !== null;
  }

  /**
   * Searches for an element that matches the conditions defined by the specified predicate, and returns the first
   * occurrence within the entire list.
   *
   * @param callable $predicate The predicate function to use when determining whether an item matches the conditions.
   * @return ?T The first item that matches the conditions defined by the specified predicate, if found; otherwise,
   * null.
   */
  public function find(callable $predicate): mixed
  {
    $result = null;

    foreach ($this as $item) {
      if ($predicate($item)) {
        $result = $item;
        break;
      }
    }

    return $result;
  }

  /**
   * Searches for an element that matches the conditions defined by the specified predicate, and returns the last
   * occurrence within the entire list.
   *
   * @param callable $predicate The predicate function to use when determining whether an item matches the conditions.
   * @return ?T The last item that matches the conditions defined by the specified predicate, if found; otherwise,
   * null.
   */
  public function findLast(callable $predicate): mixed
  {
    $result = null;

    foreach ($this->reverse() as $item) {
      if ($predicate($item)) {
        $result = $item;
        break;
      }
    }

    return $result;
  }

  /**
   * Returns an ItemList with the items in the list in reverse order.
   *
   * @return $this
   */
  protected function reverse(): static
  {
    $result = new static($this->type);

    for ($i = $this->count() - 1; $i >= 0; $i--) {
      $result->add($this->items[$i]);
    }

    return $result;
  }

  /**
   * Searches for an element that matches the conditions defined by the specified predicate, and returns the zero-based
   * index of the last occurrence within the entire list.
   *
   * @param callable $predicate The predicate function to use when determining whether an item matches the conditions.
   * @return int The zero-based index of the last occurrence of an item that matches the conditions defined by
   */
  public function findLastIndex(callable $predicate): int
  {
    for ($index = $this->count() - 1; $index >= 0; $index--)
    {
      if ($predicate($this->items[$index]))
      {
        return $index;
      }
    }

    return -1;
  }

  /**
   * Inserts the elements of a collection into the list at the specified index.
   *
   * @param int $index The zero-based index of the item to get.
   * @param ItemList<T> $items The items to insert.
   * @return void
   */
  public function insertRange(int $index, ItemList $items): void
  {
    foreach ($items as $item) {
      $this->insert($index, $item);
      $index++;
    }
  }

  /**
   * Inserts an item to the list at the specified index.
   *
   * @param int $index The zero-based index of the item to get.
   * @param T $item The item at the specified index.
   * @return void
   */
  public function insert(int $index, mixed $item): void
  {
    $this->assertItemType($item, __METHOD__);
    $normalizedIndex = $this->normalizeInsertionIndex($index);
    $this->updateSortedStateBeforeInsert($normalizedIndex, $item);
    array_splice($this->items, $normalizedIndex, 0, $item);
  }

  /**
   * Removes all the elements that match the conditions defined by the specified predicate.
   *
   * @param callable $predicate The predicate function to use when determining whether an item should be removed.
   * @return int The number of items removed from the list.
   */
  public function removeAll(callable $predicate): int
  {
    $initialCount = $this->count();

    foreach ($this->findAll($predicate) as $item) {
      $this->remove($item);
    }

    return $initialCount - $this->count();
  }

  /**
   * Searches for an element that matches the conditions defined by the specified predicate, and returns all occurrences
   * within the entire list.
   *
   * @param callable $predicate The predicate function to use when determining whether an item matches the conditions.
   * @return ItemList<T> The items that match the conditions defined by the specified predicate, if found; otherwise,
   * an empty list.
   */
  public function findAll(callable $predicate): static
  {
    $result = new static($this->type);

    foreach ($this as $item) {
      if ($predicate($item)) {
        $result->add($item);
      }
    }

    return $result;
  }

  /**
   * Removes the first occurrence of a specific item from the list.
   *
   * @param T $item The item to remove from the list.
   * @return bool true if the item was successfully removed from the list; otherwise, false. This method also returns
   * false if the item is not found in the original list.
   */
  public function remove(mixed $item): bool
  {
    $initialCount = $this->count();

    $index = $this->findIndex(fn($i) => $i === $item);

    if ($index !== -1) {
      $this->removeAt($index);
    }

    return $this->count() < $initialCount;
  }

  /**
   * Searches for an element that matches the conditions defined by the specified predicate, and returns the zero-based
   * index of the first occurrence within the entire list.
   *
   * @param callable $predicate The predicate function to use when determining whether an item matches the conditions.
   * @return int The zero-based index of the first occurrence of an item that matches the conditions defined by
   * the specified predicate, if found; otherwise, -1.
   */
  public function findIndex(callable $predicate): int
  {
    $result = -1;

    foreach ($this as $index => $item) {
      if ($predicate($item)) {
        $result = $index;
        break;
      }
    }

    return $result;
  }

  /**
   * Removes the item at the specified index of the list.
   *
   * @param int $index The zero-based index of the item to remove.
   * @return void
   */
  public function removeAt(int $index): void
  {
    array_splice($this->items, $index, 1);
    $this->refreshSortedState();
  }

  /**
   * Returns a new ItemList with the items in the list in the specified range.
   * If no length is specified, the range extends to the end of the list.
   *
   * @param int $start The zero-based index at which to start the slice.
   * @param int|null $length The number of items in the slice.
   * @return $this A new ItemList that contains the specified range of items from the original list.
   */
  public function subList(int $start, ?int $length = null): static
  {
    $length = $length ?? $this->count();
    return new static($this->type, array_slice($this->items, $start, $length));
  }

  public function offsetExists(mixed $offset): bool
  {
    $index = $this->normalizeOffset($offset);

    if ($index === null)
    {
      return false;
    }

    return array_key_exists($index, $this->items);
  }

  public function offsetGet(mixed $offset): mixed
  {
    $index = $this->normalizeOffset($offset);

    if ($index === null)
    {
      throw new TypeError('ItemList offsets must be integers.');
    }

    if (!array_key_exists($index, $this->items))
    {
      throw new OutOfBoundsException(sprintf('Undefined offset %d.', $index));
    }

    return $this->items[$index];
  }

  public function offsetSet(mixed $offset, mixed $value): void
  {
    $this->assertItemType($value, __METHOD__, 2);

    if ($offset === null)
    {
      $this->add($value);
      return;
    }

    $index = $this->normalizeOffset($offset);

    if ($index === null)
    {
      throw new TypeError('ItemList offsets must be integers.');
    }

    if ($index < 0 || $index > $this->count())
    {
      throw new OutOfBoundsException(sprintf('Offset %d is out of bounds.', $index));
    }

    if ($index === $this->count())
    {
      $this->add($value);
      return;
    }

    $this->items[$index] = $value;
    $this->refreshSortedState();
  }

  public function offsetUnset(mixed $offset): void
  {
    $index = $this->normalizeOffset($offset);

    if ($index === null)
    {
      throw new TypeError('ItemList offsets must be integers.');
    }

    if (!array_key_exists($index, $this->items))
    {
      return;
    }

    $this->removeAt($index);
  }

  public function clear(): void
  {
    parent::clear();
    $this->sortedForBinarySearch = true;
  }

  private function normalizeOffset(mixed $offset): ?int
  {
    if (is_int($offset))
    {
      return $offset;
    }

    if (is_string($offset) && ctype_digit($offset))
    {
      return (int)$offset;
    }

    return null;
  }

  private function normalizeInsertionIndex(int $index): int
  {
    if ($index < 0)
    {
      return max(0, $this->count() + $index);
    }

    return min($index, $this->count());
  }

  private function updateSortedStateBeforeAppend(mixed $item): void
  {
    if ($this->count() === 0)
    {
      $this->sortedForBinarySearch = true;
      return;
    }

    if (!$this->sortedForBinarySearch)
    {
      return;
    }

    $lastIndex = array_key_last($this->items);
    $comparison = $lastIndex === null ? 0 : $this->compareValues($this->items[$lastIndex], $item);

    if ($comparison === null || $comparison > 0)
    {
      $this->sortedForBinarySearch = false;
    }
  }

  private function updateSortedStateBeforeInsert(int $index, mixed $item): void
  {
    if ($this->count() === 0)
    {
      $this->sortedForBinarySearch = true;
      return;
    }

    if (!$this->sortedForBinarySearch)
    {
      return;
    }

    if ($index > 0)
    {
      $beforeComparison = $this->compareValues($this->items[$index - 1], $item);

      if ($beforeComparison === null || $beforeComparison > 0)
      {
        $this->sortedForBinarySearch = false;
        return;
      }
    }

    if ($index < $this->count())
    {
      $afterComparison = $this->compareValues($item, $this->items[$index]);

      if ($afterComparison === null || $afterComparison > 0)
      {
        $this->sortedForBinarySearch = false;
      }
    }
  }

  private function refreshSortedState(): void
  {
    $this->sortedForBinarySearch = true;

    for ($index = 1; $index < $this->count(); $index++)
    {
      $comparison = $this->compareValues($this->items[$index - 1], $this->items[$index]);

      if ($comparison === null || $comparison > 0)
      {
        $this->sortedForBinarySearch = false;
        return;
      }
    }
  }

  private function compareValues(mixed $left, mixed $right): ?int
  {
    if (get_debug_type($left) !== get_debug_type($right))
    {
      return null;
    }

    if (is_scalar($left) || $left === null)
    {
      return $left <=> $right;
    }

    return null;
  }
}
