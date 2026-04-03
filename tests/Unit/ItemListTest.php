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

it('can be traversed with foreach', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);
  $items = [];

  foreach ($list as $index => $item) {
    $items[$index] = $item;
  }

  expect($items)->toBe([
    0 => 'foo',
    1 => 'bar',
    2 => 'baz',
  ]);
});


it('keeps binarySearch on the direct search path for sorted lists', function () {
  $list = new class('integer', [1, 2, 3, 4]) extends ItemList {
    public int $findIndexCalls = 0;

    public function findIndex(callable $predicate): int
    {
      $this->findIndexCalls++;
      return parent::findIndex($predicate);
    }
  };

  expect($list->binarySearch(3))->toBe(2)
    ->and($list->findIndexCalls)->toBe(0);
});

it('falls back to a correct index when binarySearch is used on an unsorted list', function () {
  $list = new ItemList('integer', [2, 1, 3]);

  expect($list->binarySearch(1))->toBe(1)
    ->and($list->binarySearch(3))->toBe(2)
    ->and($list->binarySearch(9))->toBe(-1);
});

it('returns the original index for the last matching item', function () {
  $list = new ItemList('string', ['a', 'b', 'c', 'b']);

  expect($list->findLastIndex(fn($item) => $item === 'b'))->toBe(3);
});

it('preserves subclasses when findAll returns a filtered list', function () {
  eval('namespace Assegai\Collections\Tests; class CustomItemList extends \Assegai\Collections\ItemList {}');

  $class = 'Assegai\Collections\Tests\CustomItemList';
  $list = new $class('string', ['alpha', 'beta']);
  $filtered = $list->findAll(fn($item) => str_starts_with($item, 'a'));

  expect($filtered)->toBeInstanceOf($class)
    ->and($filtered->toArray())->toBe(['alpha']);
});

it('supports array-style reads and writes', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  expect($list[0])->toBe('foo')
    ->and(isset($list[1]))->toBeTrue()
    ->and(isset($list[10]))->toBeFalse();

  $list[1] = 'updated';
  $list[] = 'tail';

  expect($list->toArray())->toBe(['foo', 'updated', 'baz', 'tail']);
});

it('supports unsetting items by offset', function () {
  $list = new ItemList('string', ['foo', 'bar', 'baz']);

  unset($list[1]);

  expect($list->toArray())->toBe(['foo', 'baz']);
});

it('rejects invalid array-style offsets and values', function () {
  $list = new ItemList('string', ['foo', 'bar']);

  expect(fn() => $list['name'])->toThrow(TypeError::class)
    ->and(fn() => $list[5])->toThrow(OutOfBoundsException::class)
    ->and(fn() => $list['name'] = 'baz')->toThrow(TypeError::class)
    ->and(fn() => $list[5] = 'baz')->toThrow(OutOfBoundsException::class)
    ->and(fn() => $list[] = 123)->toThrow(TypeError::class);
});
