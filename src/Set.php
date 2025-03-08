<?php

namespace Assegai\Collections;

use Assegai\Collections\Interfaces\CollectionInterface;
use Assegai\Collections\Interfaces\SetInterface;

/**
 * Represents a set of items. A Set is a collection of unique elements that does not allow duplicates.
 *
 * @template T
 */
class Set extends AbstractCollection implements SetInterface
{
  /**
   * @inheritDoc
   */
  public function addAll(mixed ...$items): void
  {
    foreach ($items as $item) {
      if (is_iterable($item)) {
        foreach ($item as $i) {
          $this->add($i);
        }

        continue;
      }

      $this->add($item);
    }
  }

  /**
   * @inheritDoc
   */
  public function add(mixed $item): void
  {
    if ($this->doesNotContain($item)) {
      $this->items[] = $item;
    }
  }

  /**
   * @inheritDoc
   */
  public function exceptWith(iterable $other): void
  {
    foreach ($other as $item) {
      $this->remove($item);
    }
  }

  /**
   * @inheritDoc
   */
  public function remove(mixed $item): void
  {
    if ($this->contains($item)) {
      $this->items = array_filter($this->items, fn($i) => $i !== $item);
    }
  }

  /**
   * @inheritDoc
   */
  public function intersectWith(iterable $other): void
  {
    // If other implements CollectionInterface, then we can use the contains method.
    if ($other instanceof CollectionInterface) {
      foreach ($this->items as $item) {
        if ($other->contains($item) === false) {
          $this->remove($item);
        }
      }
    } else {
      if (is_array($other)) {
        foreach ($this->items as $item) {
          if (in_array($item, $other) === false) {
            $this->remove($item);
          }
        }
      } else {
        foreach ($this->items as $index => $item) {
          if (isset($other->$index) === false || $other->$index !== $item) {
            $this->remove($item);
          }
        }
      }
    }

    // Finally add missing items from other to the current set.
    foreach ($other as $item) {
      $this->add($item);
    }
  }

  /**
   * @inheritDoc
   */
  public function isSubsetOf(iterable $other): bool
  {
    foreach ($other as $item) {
      if ($this->doesNotContain($item)) {
        return false;
      }
    }

    return true;
  }

  /**
   * @inheritDoc
   */
  public function overlaps(iterable $other): bool
  {
    foreach ($other as $item) {
      if ($this->contains($item)) {
        return true;
      }
    }

    return false;
  }

  /**
   * @inheritDoc
   */
  public function unionWith(iterable $other): void
  {
    foreach ($other as $item) {
      $this->add($item);
    }
  }

  /**
   * @inheritDoc
   */
  public function __serialize(): array
  {
    return $this->items;
  }

  /**
   * @inheritDoc
   */
  public function __unserialize(array $data): void
  {
    $this->items = $data;
  }
}