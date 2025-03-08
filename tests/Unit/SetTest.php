<?php

use Assegai\Collections\Set;

describe('Set', function() {
  it('can add items to the set using the constructor',  function () {
    $set = new Set('string', ['foo', 'bar', 'baz']);

    expect($set->count())->toBe(3)
      ->and($set->toArray())->toBe(['foo', 'bar', 'baz']);
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

  it('can remove items from the set',  function () {
    $set = new Set('string', ['foo', 'bar', 'baz']);
    $set->remove('bar');

    expect($set->count())->toBe(2)
      ->and($set->toArray())->toBe([0 => 'foo', 2 => 'baz']);
  });
});