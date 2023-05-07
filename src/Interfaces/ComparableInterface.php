<?php

namespace Assegai\Collections\Interfaces;

/**
 * Represents an object that can be compared for equality with another object.
 *
 * @template T
 */
interface ComparableInterface extends EquatableInterface
{
  /**
   * Compares the current object with another object of the same type.
   *
   * @template T
   * @param T $other The object to compare with the current object.
   * @return int A value that indicates the relative order of the objects being compared. The return value has these meanings:
   *             - less than zero: The current object is less than the other parameter.
   *             - zero: The current object is equal to the other.
   *             - greater than zero: The current object is greater than the other.
   */
  public function compareTo(mixed $other): int;
}