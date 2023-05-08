<?php

namespace Assegai\Collections;

use TypeError;

/**
 * Represents a first-in, last-out (FILO) collection of items.
 *
 * @template T The type of items in the stack.
 */
class Stack extends AbstractCollection
{
  /**
   * Constructs a new Stack instance.
   *
   * @template T
   * @param class-string<T> $type The type of the stack.
   * @var array $items The items in the stack.
   */
  public function __construct(
    string $type,
    array $items = []
  )
  {
    parent::__construct($type);

    foreach ($items as $item)
    {
      $this->push($item);
    }
  }

  /**
   * Adds an item to the stack.
   *
   * @template T type of item
   * @param T $item The item to add.
   * @return void
   */
  public function push(mixed $item): void
  {
    $typeName = match (true) {
      is_object($item) => get_class($item),
      default => gettype($item),
    };

    if ($typeName !== $this->type && is_subclass_of($item, $this->type) === false)
    {
      throw new TypeError($this->getTypeErrorMessage(__METHOD__, $typeName));
    }

    $this->items[] = $item;
  }

  /**
   * Adds multiple items to the stack.
   *
   * @template T
   * @param T ...$items The items to add.
   * @return void
   */
  public function pushAll(mixed ...$items): void
  {
    foreach ($items as $item)
    {
      $this->push($item);
    }
  }

  /**
   * Removes an item from the stack.
   *
   * @template T
   * @return T The removed item.
   */
  public function pop(): mixed
  {
    return array_pop($this->items);
  }

  /**
   * Gets the item at the top of the stack.
   *
   * @template T
   * @return T The item at the top of the stack.
   */
  public function peek(): mixed
  {
    if ($this->isEmpty())
    {
      return null;
    }

    return $this->items[count($this->items) - 1];
  }
}