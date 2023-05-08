<?php

namespace Assegai\Collections;

use TypeError;

/**
 * Represents a collection of items.
 *
 * @template T
 */
class Collection extends AbstractCollection
{
  /**
   * Adds an item to the collection.
   * @template T
   * @param T $item The item to add.
   * @return void
   */
  public function add(mixed $item): void
  {
    $typeName = match (true) {
      is_object($item) => get_class($item),
      default => gettype($item),
    };

    if ($typeName !== $this->type)
    {
      throw new TypeError( $this->getTypeErrorMessage(__METHOD__, $typeName) );
    }

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
    $this->items = array_filter($this->items, fn($i) => $i !== $item);
  }
}