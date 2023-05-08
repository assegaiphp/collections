<?php

namespace Assegai\Collections\Interfaces;

/**
 * Represents a first-in, first-out collection of items.
 *
 * @template T
 */
interface QueueInterface extends CollectionInterface
{
  /**
   * Adds an item to the end of the queue.
   *
   * @template T The type of the item to add.
   * @param T $other The item to add.
   * @return void
   */
  public function enqueue(mixed $other): void;

  /**
   * Removes the item at the beginning of the queue.
   *
   * @template T The type of the item to remove.
   * @return T The removed item.
   */
  public function dequeue(): mixed;

  /**
   * Gets the item at the beginning of the queue without removing it.
   *
   * @template T The type of the item to peek.
   * @return ?T The item at the beginning of the queue or null.
   */
  public function peek(): mixed;
}