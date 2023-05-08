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

test('Check if an ItemList collection containes an item using a predicate', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list->exists(fn($item) => $item === 'bar'))->toBe(true)
    ->and($list->exists(fn($item) => $item === 'baz'))->toBe(true)
    ->and($list->exists(fn($item) => $item === 'monkey'))->toBe(false);
});

test('Clear an ItemList collection', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $list->clear();

  expect($list->count())->toBe(0)
    ->and($list->toArray())->toBe([]);
});

test('Find an item in an ItemList collection', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list->find(fn($item) => $item === 'bar'))->toBe('bar')
    ->and($list->find(fn($item) => $item === 'baz'))->toBe('baz')
    ->and($list->find(fn($item) => $item === 'monkey'))->toBe(null)
    ->and($list->findAll(fn($item) => $item === 'bar'))->toEqual(new ItemList('string', ['bar']))
    ->and($list->findIndex(fn($item) => $item === 'bar'))->toBe(1)
    ->and($list->findIndex(fn($item) => $item === 'monkey'))->toBe(-1)
    ->and($list->findLast(fn($item) => $item === 'bar'))->toBe('bar')
    ->and($list->findLast(fn($item) => $item === 'monkey'))->toBe(null)
    ->and($list->findLastIndex(fn($item) => $item === 'bar'))->toBe(1)
    ->and($list->findLastIndex(fn($item) => $item === 'monkey'))->toBe(-1);
});

test('Add items to an ItemList collection with the insert method', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $list->insert(1, 'monkey');

  expect($list->count())->toBe(4)
    ->and($list->toArray())->toBe(['foo', 'monkey', 'bar', 'baz']);

  $list->insertRange(1, new ItemList('string', ['a', 'b', 'c']));

  expect($list->count())->toBe(7)
    ->and($list->toArray())->toBe(['foo', 'a', 'b', 'c', 'monkey', 'bar', 'baz']);
});

test('Remove items from an ItemList collection', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $list->remove('bar');

  expect($list->count())->toBe(2)
    ->and($list->toArray())->toBe(['foo', 'baz']);
});