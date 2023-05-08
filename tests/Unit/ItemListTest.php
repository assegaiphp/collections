<?php

use Assegai\Collections\ItemList;

test('Add items to an ItemList collection with constructor', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list->count())->toBe(3)
    ->and($list->toArray())->toBe(['foo', 'bar', 'baz']);
});

test('Add items to an ItemList collection', function () {
  $list = new ItemList('string');
  $list->add('foo');
  $list->add('bar');
  $list->add('baz');

  expect($list->count())->toBe(3)
    ->and($list->toArray())->toBe(['foo', 'bar', 'baz']);
});

test('Find items using the binarySearch method', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list->binarySearch('bar'))->toBe(1)
    ->and($list->binarySearch('baz'))->toBe(2)
    ->and($list->binarySearch('monkey'))->toBe(-1);
});

test('Check if an ItemList collection contains an item', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list->contains('bar'))->toBe(true)
    ->and($list->contains('baz'))->toBe(true)
    ->and($list->contains('monkey'))->toBe(false);
});

test('Clear an ItemList collection', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $list->clear();

  expect($list->count())->toBe(0)
    ->and($list->toArray())->toBe([]);
});

test('Remove items from an ItemList collection', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $list->remove('bar');

  expect($list->count())->toBe(2)
    ->and($list->toArray())->toBe(['foo', 'baz']);
});