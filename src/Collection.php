<?php

namespace Assegai\Collections;

/**
 * Represents a collection of items.
 *
 * @template T
 */
class Collection extends AbstractCollection
{
  /**
   * Constructs a new Collection instance and validates the provided items.
   *
   * @param string $type The type of items the collection accepts.
   * @param array $items The initial items.
   */
  public function __construct(string $type, array $items = [])
  {
    parent::__construct($type);

    foreach ($items as $item)
    {
      $this->add($item);
    }
  }

  /**
   * Adds an item to the collection.
   * @template T
   * @param T $item The item to add.
   * @return void
   */
  public function add(mixed $item): void
  {
    $this->assertItemType($item, __METHOD__);

    $this->items[] = $item;
  }

  /**
   * Removes an item from the collection.
   * @template T The type of the item.
   * @param T $item The item to remove.
   * @return void
   */
  public function remove(mixed $item): void
  {
    $this->items = array_values(array_filter($this->items, fn($i) => $i !== $item));
  }
}
