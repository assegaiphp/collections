<?php

use Assegai\Collections\Set;

describe('Set', function() {
  it('can add items to the set using the constructor',  function () {
    $set = new Set('string', ['foo', 'bar', 'baz']);

    expect($set->count())->toBe(3)
      ->and($set->toArray())->toBe(['foo', 'bar', 'baz']);
  });

  it('validates constructor items against the declared type', function () {
    expect(fn() => new Set('integer', [1, 'two']))
      ->toThrow(TypeError::class);
  });

  it('can add items to the set',  function () {
    $set = new Set('string');
    $set->add('foo');
    $set->add('bar');
    $set->add('baz');

    expect($set->count())->toBe(3)
      ->and($set->toArray())->toBe(['foo', 'bar', 'baz']);

    $set->addAll(['foo1', 'bar1', 'baz1']);

    expect($set->count())->toBe(6)
      ->and($set->toArray())->toBe(['foo', 'bar', 'baz', 'foo1', 'bar1', 'baz1']);

    $set->addAll(['foo', 'bar', 'baz']);

    expect($set->count())->toBe(6)
      ->and($set->toArray())->toBe(['foo', 'bar', 'baz', 'foo1', 'bar1', 'baz1']);
  });

  it('rejects items that do not match the declared type', function () {
    $set = new Set('integer');

    expect(fn() => $set->add('foo'))->toThrow(TypeError::class);
  });

  it('can remove items from the set',  function () {
    $set = new Set('string', ['foo', 'bar', 'baz']);
    $set->remove('bar');

    expect($set->count())->toBe(2)
      ->and($set->toArray())->toBe(['foo', 'baz']);
  });

  it('keeps set uniqueness when mapping values', function () {
    $set = new Set('integer', [1, 2]);
    $mappedSet = $set->map(fn($item) => 1);

    expect($mappedSet)->toBeInstanceOf(Set::class)
      ->and($mappedSet->toArray())->toBe([1]);
  });

  it('can intersect with another iterable without adding extra values', function () {
    $set = new Set('integer', [1, 2, 3]);

    $set->intersectWith([2, 3, 4]);

    expect($set->toArray())->toBe([2, 3]);
  });

  it('can correctly determine whether it is a subset of another iterable', function () {
    $subset = new Set('integer', [1, 2]);
    $superset = [1, 2, 3];
    $differentSet = [2, 3];

    expect($subset->isSubsetOf($superset))->toBeTrue()
      ->and($subset->isSubsetOf($differentSet))->toBeFalse();
  });

  it('preserves set invariants across serialization', function () {
    $set = new Set('integer', [1, 2]);
    $restoredSet = unserialize(serialize($set));

    expect($restoredSet)->toBeInstanceOf(Set::class)
      ->and($restoredSet->toArray())->toBe([1, 2])
      ->and(fn() => $restoredSet->add('three'))->toThrow(TypeError::class);

    $restoredSet->add(2);

    expect($restoredSet->toArray())->toBe([1, 2]);
  });
});
