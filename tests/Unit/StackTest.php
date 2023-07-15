<?php

use Assegai\Collections\Stack;

it('can add items to the collection using the constructor', function () {
  $stack = new Stack('string', ['foo', 'bar', 'baz']);

  expect($stack->count())->toBe(3)
    ->and($stack->toArray())->toBe(['foo', 'bar', 'baz']);
});

it('can add items to the collection', function () {
  $stack = new Stack('string');
  $stack->push('foo');
  $stack->push('bar');
  $stack->push('baz');

  expect($stack->count())->toBe(3)
    ->and($stack->toArray())->toBe(['foo', 'bar', 'baz']);

  $stack->pushAll(['foo1', 'bar1', 'baz1']);

  expect($stack->count())->toBe(6)
    ->and($stack->toArray())->toBe(['foo', 'bar', 'baz', 'foo1', 'bar1', 'baz1']);

  $stack->pushAll(['foo', 'bar', 'baz']);

  expect($stack->count())->toBe(9)
    ->and($stack->toArray())->toBe(['foo', 'bar', 'baz', 'foo1', 'bar1', 'baz1', 'foo', 'bar', 'baz']);
});

it('can remove items from the collection', function () {
  $stack = new Stack('string', ['foo', 'bar', 'baz']);
  $stack->pop();

  expect($stack->count())->toBe(2)
    ->and($stack->toArray())->toBe(['foo', 'bar']);
});

it('can check for the item at the top of the stack', function () {
  $queue = new Stack('string', ['foo', 'bar', 'baz']);

  expect($queue->peek())->toBe('baz');
});