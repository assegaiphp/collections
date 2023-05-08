<?php

namespace Assegai\Collections;

use TypeError;

/**
 * Represents a strongly typed list of items that can be accessed by index. Provides methods to search, sort, and
 * manipulate lists.
 *
 * @template T
 */
class ItemList extends AbstractCollection
{
  /**
   * Constructs a new ItemList instance.
   *
   * @template T The type of the items in the list.
   * @param class-string<T> $type The type of the items in the list.
   * @param array $items The items in the list.
   */
  public function __construct(
    string $type,
    array $items = []
  )
  {
    parent::__construct($type);

    foreach ($items as $item)
    {
      $this->add($item);
    }
  }

  /**
   * Adds an item to the list.
   *
   * @template T The type of the item.
   * @param T $item The item to add.
   * @return void
   */
  public function add(mixed $item): void
  {
    $typeName = match (true) {
      is_object($item) => get_class($item),
      default => gettype($item),
    };

    if ($typeName !== $this->type && is_subclass_of($item, $this->type) === false)
    {
      throw new TypeError( $this->getTypeErrorMessage(__METHOD__, $typeName) );
    }

    $this->items[] = $item;
  }

  /**
   * Uses a binary search algorithm to locate a specific element in the list.
   *
   * @template T The type of the item.
   * @param T $item The item to search for.
   * @return int The index of the item if found; otherwise, -1.
   */
  public function binarySearch(mixed $item): int
  {
    $low = 0;
    $high = $this->count() - 1;

    while ($low <= $high)
    {
      $mid = (int) (($low + $high) / 2);
      $guess = $this->items[$mid];

      if ($guess === $item)
      {
        return $mid;
      }

      if ($guess > $item)
      {
        $high = $mid - 1;
      }
      else
      {
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
   * @template T The type of the item.
   * @param callable $predicate The predicate function to use when determining whether an item matches the conditions.
   * @return ?T The first item that matches the conditions defined by the specified predicate, if found; otherwise,
   * null.
   */
  public function find(callable $predicate): mixed
  {
    $result = null;

    foreach ($this as $item)
    {
      if ($predicate($item))
      {
        $result = $item;
        break;
      }
    }

    return $result;
  }

  /**
   * Searches for an element that matches the conditions defined by the specified predicate, and returns all occurrences
   * within the entire list.
   *
   * @template T The type of the item.
   * @param callable $predicate The predicate function to use when determining whether an item matches the conditions.
   * @return ItemList<T> The items that match the conditions defined by the specified predicate, if found; otherwise,
   * an empty list.
   */
  public function findAll(callable $predicate): static
  {
    $result = new ItemList($this->type);

    foreach ($this as $item)
    {
      if ($predicate($item))
      {
        $result->add($item);
      }
    }

    return $result;
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

    foreach ($this as $index => $item)
    {
      if ($predicate($item))
      {
        $result = $index;
        break;
      }
    }

    return $result;
  }

  /**
   * Searches for an element that matches the conditions defined by the specified predicate, and returns the last
   * occurrence within the entire list.
   *
   * @template T The type of the item.
   * @param callable $predicate The predicate function to use when determining whether an item matches the conditions.
   * @return ?T The last item that matches the conditions defined by the specified predicate, if found; otherwise,
   * null.
   */
  public function findLast(callable $predicate): mixed
  {
    $result = null;

    foreach ($this->reverse() as $item)
    {
      if ($predicate($item))
      {
        $result = $item;
        break;
      }
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
    $result = -1;

    foreach ($this->reverse() as $index => $item)
    {
      if ($predicate($item))
      {
        $result = $index;
        break;
      }
    }

    return $result;
  }

  /**
   * Inserts an item to the list at the specified index.
   *
   * @template T The type of the item.
   * @param int $index The zero-based index of the item to get.
   * @param T $item The item at the specified index.
   * @return void
   */
  public function insert(int $index, mixed $item): void
  {
    $typeName = match (true) {
      is_object($item) => get_class($item),
      default => gettype($item),
    };

    if ($typeName !== $this->type && is_subclass_of($item, $this->type) === false)
    {
      throw new TypeError( $this->getTypeErrorMessage(__METHOD__, $typeName) );
    }

    array_splice($this->items, $index, 0, $item);
  }

  /**
   * Inserts the elements of a collection into the list at the specified index.
   *
   * @template T The type of the item.
   * @param int $index The zero-based index of the item to get.
   * @param ItemList<T> $items The items to insert.
   * @return void
   */
  public function insertRange(int $index, ItemList $items): void
  {
    foreach ($items as $item)
    {
      $this->insert($index, $item);
      $index++;
    }
  }

  /**
   * Removes the first occurrence of a specific item from the list.
   *
   * @template T The type of the item.
   * @param T $item The item to remove from the list.
   * @return bool true if the item was successfully removed from the list; otherwise, false. This method also returns
   * false if the item is not found in the original list.
   */
  public function remove(mixed $item): bool
  {
    $initialCount = $this->count();

    $index = $this->findIndex(fn($i) => $i === $item);

    if ($index !== -1)
    {
      $this->removeAt($index);
    }

    return $this->count() < $initialCount;
  }

  /**
   * Removes all the elements that match the conditions defined by the specified predicate.
   *
   * @template T The type of the item.
   * @param callable $predicate The predicate function to use when determining whether an item should be removed.
   * @return int The number of items removed from the list.
   */
  public function removeAll(callable $predicate): int
  {
    $initialCount = $this->count();

    foreach ($this->findAll($predicate) as $item)
    {
      $this->remove($item);
    }

    return $initialCount - $this->count();
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
  }

  /**
   * Returns a new ItemList with the items in the list in the specified range.
   * If no length is specified, the range extends to the end of the list.
   *
   * @param int $start The zero-based index at which to start the slice.
   * @param int|null $length The number of items in the slice.
   * @return $this A new ItemList that contains the specified range of items from the original list.
   */
  public function subList(int $start, int $length = null): static
  {
    $length = $length ?? $this->count();
    return new static($this->type, array_slice($this->items, $start, $length));
  }

  /**
   * Returns an ItemList with the items in the list in reverse order.
   *
   * @template T
   * @return $this
   */
  protected function reverse(): static
  {
    $result = new static($this->type);

    for ($i = $this->count() - 1; $i >= 0; $i--)
    {
      $result->add($this->items[$i]);
    }

    return $result;
  }
}