<?php

use Assegai\Collections\ItemList;

it('can add items to the collection using the constructor', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list->count())->toBe(3)
    ->and($list->toArray())->toBe(['foo', 'bar', 'baz']);
});

it('can add items to the collection', function () {
  $list = new ItemList('string');
  $list->add('foo');
  $list->add('bar');
  $list->add('baz');

  expect($list->count())->toBe(3)
    ->and($list->toArray())->toBe(['foo', 'bar', 'baz']);
});

it('can find items in the collection using the binarySearch method', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list->binarySearch('bar'))->toBe(1)
    ->and($list->binarySearch('baz'))->toBe(2)
    ->and($list->binarySearch('monkey'))->toBe(-1);
});

it('can check if the collection contains an item', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list->contains('bar'))->toBe(true)
    ->and($list->contains('baz'))->toBe(true)
    ->and($list->contains('monkey'))->toBe(false);
});

it('can check if an item in the collection exists using a predicate', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list->exists(fn($item) => $item === 'bar'))->toBe(true)
    ->and($list->exists(fn($item) => $item === 'baz'))->toBe(true)
    ->and($list->exists(fn($item) => $item === 'monkey'))->toBe(false);
});

it('can clear the collection', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $list->clear();

  expect($list->count())->toBe(0)
    ->and($list->toArray())->toBe([]);
});

it('can find items in the collection using a predicate', function () {
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

it('can add items to the collection with the insert method', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $list->insert(1, 'monkey');

  expect($list->count())->toBe(4)
    ->and($list->toArray())->toBe(['foo', 'monkey', 'bar', 'baz']);

  $list->insertRange(1, new ItemList('string', ['a', 'b', 'c']));

  expect($list->count())->toBe(7)
    ->and($list->toArray())->toBe(['foo', 'a', 'b', 'c', 'monkey', 'bar', 'baz']);
});

it('can remove items from the collection', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $list->remove('bar');

  expect($list->count())->toBe(2)
    ->and($list->toArray())->toBe(['foo', 'baz']);
});

it('can create a subset of the collection', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $subset = $list->subList(1, 2);

  expect($subset->count())->toBe(2)
    ->and($subset->toArray())->toBe(['bar', 'baz']);
});