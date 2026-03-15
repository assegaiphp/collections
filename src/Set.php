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
   * Constructs a new Set instance and normalizes the provided items.
   *
   * @param string $type The type of items the set accepts.
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
    $this->assertItemType($item, __METHOD__);

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
      $this->items = array_values(array_filter($this->items, fn($i) => $i !== $item));
    }
  }

  /**
   * @inheritDoc
   */
  public function intersectWith(iterable $other): void
  {
    $otherItems = $this->normalizeIterable($other);

    $this->items = array_values(
      array_filter(
        $this->items,
        static fn($item) => in_array($item, $otherItems, true)
      )
    );
  }

  /**
   * @inheritDoc
   */
  public function isSubsetOf(iterable $other): bool
  {
    $otherItems = $this->normalizeIterable($other);

    foreach ($this->items as $item) {
      if (!in_array($item, $otherItems, true)) {
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
    return parent::__serialize();
  }

  /**
   * @inheritDoc
   */
  public function __unserialize(array $data): void
  {
    parent::__unserialize($data);
  }

  /**
   * Converts any iterable into a plain list of values for strict set comparisons.
   *
   * @param iterable $other
   * @return array
   */
  private function normalizeIterable(iterable $other): array
  {
    return match (true) {
      $other instanceof CollectionInterface => $other->toArray(),
      is_array($other) => array_values($other),
      default => array_values(iterator_to_array($other, false)),
    };
  }
}
