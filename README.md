<div align="center" style="padding-bottom: 48px">
    <a href="https://assegaiphp.com/" target="blank"><img src="https://assegaiphp.com/images/logos/logo-cropped.png" width="200" alt="Assegai Logo"></a>
</div>

<p style="text-align: center">A progressive <a href="https://php.net">PHP</a> framework for building effecient and scalable server-side applications.</p>

# AssegaiPHP Collections

A powerful and easy-to-use library for creating and managing collections of related objects in AssegaiPHP. This library provides a simple and intuitive interface for working with arrays of objects, making it easy to perform common operations such as filtering, mapping, and reducing.

## Installation

You can install the library using composer by running the following command:

```bash
composer require assegaiphp/collections
```

## Usage

To create a new collection, you can use the `Assegai\Collections\Collection` class. This class provides a simple and intuitive interface for working with arrays of objects, making it easy to perform common operations such as filtering, mapping, and reducing.

Here's an example of how to create a new collection and add some items to it:

```PHP
use Assegai\Collections\Collection;

$collection = new Collection();
$collection->add(1);
$collection->add(2);
$collection->add(3);
```

Once you have a collection, you can use the various methods provided by the class to manipulate the items in the collection. For example, you can use the `filter` method to filter the items in the collection based on a certain condition:

```PHP
$filteredCollection = $collection->filter(function($item) {
    return $item > 1;
});
```

The `map` method can be used to transform the items in the collection:

```PHP
$mappedCollection = $collection->map(function($item) {
    return $item * 2;
});
```

You can also use the `reduce` method to reduce the collection to a single value:

```PHP
$sum = $collection->reduce(function($carry, $item) {
    return $carry + $item;
}, 0);
```

## API

The API of the `Assegai\Collections\Collection` class is designed to be simple and intuitive. It provides the following methods:

- `add(mixed $item)`: Add an item to the collection
- `remove(mixed $item)`: Remove an item from the collection
- `filter(callable $callback)`: Filter the items in collection based on a callback function.