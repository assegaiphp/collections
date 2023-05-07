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
   * @param T $other The item to add.
   * @return void
   */
  public function add(mixed $other): void
  {
    $typeName = match (true) {
      is_object($other) => get_class($other),
      default => gettype($other),
    };

    if ($typeName !== $this->type)
    {
      throw new TypeError( $this->getTypeErrorMessage(__METHOD__, $typeName) );
    }

    $this->items[] = $other;
  }
}