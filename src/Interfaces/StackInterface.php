<?php

namespace Assegai\Collections\Interfaces;

/**
 * Represents a last-in, first-out (LIFO) collection of items.
 *
 * @template T
 */
interface StackInterface extends CollectionInterface
{
  /**
   * Adds an item to the stack.
   *
   * @template T
   * @param T $item The item to add.
   * @return void
   */
  public function push(mixed $item): void;

  /**
   * Adds multiple items to the stack.
   *
   * @template T
   * @param T ...$items The items to add.
   * @return void
   */
  public function pushAll(mixed ...$items): void;

  /**
   * Removes an item from the stack.
   *
   * @template T
   * @return T The removed item.
   */
  public function pop(): mixed;

  /**
   * Gets the item at the top of the stack.
   *
   * @template T
   * @return T The item at the top of the stack.
   */
  public function peek(): mixed;
}