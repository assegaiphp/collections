<?php

namespace Assegai\Collections\Interfaces;

/**
 * Represents an object that can be compared for equality with another object.
 *
 * @template T
 */
interface EquatableInterface
{
  /**
   * Determines whether the current object is equal to another object.
   *
   * @template T
   * @param T $other The object to compare with the current object.
   * @return bool true if the current object is equal to the other parameter; otherwise, false.
   */
  public function equals(mixed $other): bool;
}