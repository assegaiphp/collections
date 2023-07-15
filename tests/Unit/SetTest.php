<?php

use Assegai\Collections\Set;

it('can add items to the collection using the constructor',  function () {
  $set = new Set('string', ['foo', 'bar', 'baz']);

  expect($set->count())->toBe(3)
    ->and($set->toArray())->toBe(['foo', 'bar', 'baz']);
});

it('can add items to the collection',  function () {
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

it('can remove items from the collection',  function () {
  $set = new Set('string', ['foo', 'bar', 'baz']);
  $set->remove('bar');

  expect($set->count())->toBe(3)
    ->and($set->toArray())->toBe(['foo', 'baz']);
});