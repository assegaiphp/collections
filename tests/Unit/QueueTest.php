<?php

use Assegai\Collections\Queue;

it('can add items to the collection using the constructor',  function () {
  $queue = new Queue('string', ['foo', 'bar', 'baz']);

  expect($queue->count())->toBe(3)
    ->and($queue->toArray())->toBe(['foo', 'bar', 'baz']);
});

it('can add items to the collection',  function () {
  $queue = new Queue('string');
  $queue->enqueue('foo');
  $queue->enqueue('bar');
  $queue->enqueue('baz');

  expect($queue->count())->toBe(3)
    ->and($queue->toArray())->toBe(['foo', 'bar', 'baz']);
});

it('can remove items from the collection',  function () {
  $queue = new Queue('string', ['foo', 'bar', 'baz']);
  $queue->dequeue();

  expect($queue->count())->toBe(2)
    ->and($queue->toArray())->toBe(['bar', 'baz']);
});

it('can check if the collection contains an item',  function () {
  $queue = new Queue('string', ['foo', 'bar', 'baz']);

  expect($queue->contains('bar'))->toBe(true)
    ->and($queue->contains('baz'))->toBe(true)
    ->and($queue->contains('monkey'))->toBe(false);
});

it('can check for the item at the front of the queue', function () {
  $queue = new Queue('string', ['foo', 'bar', 'baz']);

  expect($queue->peek())->toBe('foo');
});