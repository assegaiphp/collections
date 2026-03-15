<?php

use Assegai\Collections\Collection;

it('validates constructor items against the declared type', function () {
  expect(fn() => new Collection('integer', [1, 'two']))
    ->toThrow(TypeError::class);
});

it('reindexes items after removal', function () {
  $collection = new Collection('string', ['foo', 'bar', 'baz']);

  $collection->remove('bar');

  expect($collection->toArray())->toBe(['foo', 'baz']);
});

it('returns false when checking equality against a non-collection value', function () {
  $collection = new Collection('integer', [1, 2, 3]);

  expect($collection->equals('not a collection'))->toBeFalse();
});

it('preserves the collection type across serialization', function () {
  $collection = new Collection('integer', [1, 2]);
  $restoredCollection = unserialize(serialize($collection));

  expect($restoredCollection)->toBeInstanceOf(Collection::class)
    ->and($restoredCollection->toArray())->toBe([1, 2])
    ->and(fn() => $restoredCollection->add('three'))->toThrow(TypeError::class);

  $restoredCollection->add(3);

  expect($restoredCollection->toArray())->toBe([1, 2, 3]);
});
