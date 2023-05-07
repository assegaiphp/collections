<?php

namespace Assegai\Collections;

use Assegai\Collections\Interfaces\QueueInterface;
use TypeError;

/**
 * Represents a first-in, first-out collection of items.
 */
class Queue extends AbstractCollection implements QueueInterface
{
  /**
   * Creates a new Queue instance.
   *
   * @template T The type of the items in the queue.
   * @param class-string<T> $type The type of the queue.
   * @param array $items The items to add to the queue.
   */
  public function __construct(
    protected string $type,
    array $items = []
  )
  {
    parent::__construct($type);

    foreach ($items as $item)
    {
      $this->enqueue($item);
    }
  }

  /**
   * Adds an item to the end of the queue.
   *
   * @template T The type of the item to add.
   * @param T $other The item to add.
   * @return void
   */
  public function enqueue(mixed $other): void
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

  /**
   * Removes the item at the beginning of the queue.
   *
   * @template T The type of the item to remove.
   * @return T The removed item.
   */
  public function dequeue(): mixed
  {
    return array_shift($this->items);
  }

  /**
   * Gets the item at the beginning of the queue.
   *
   * @template T The type of the item to peek.
   * @return ?T The item at the beginning of the queue.
   */
  public function peek(): mixed
  {
    if (!isset($this->items[0]))
    {
      return null;
    }

    return $this->items[0];
  }
}