<div align="center" style="padding-bottom: 48px">
  <a href="https://assegaiphp.com/" target="blank"><img src="https://assegaiphp.com/images/logos/logo-cropped.png" width="200" alt="AssegaiPHP Logo"></a>
</div>

<p align="center">
  <a href="https://github.com/assegaiphp/collections/releases"><img alt="Latest release" src="https://img.shields.io/github/v/release/assegaiphp/collections?display_name=tag&sort=semver&style=flat-square"></a>
  <a href="https://github.com/assegaiphp/collections/actions/workflows/php.yml"><img alt="Tests" src="https://img.shields.io/github/actions/workflow/status/assegaiphp/collections/php.yml?branch=main&label=tests&style=flat-square"></a>
  <img alt="PHP 8.4+" src="https://img.shields.io/badge/PHP-8.4%2B-777BB4?style=flat-square&logo=php&logoColor=white">
  <a href="https://github.com/assegaiphp/collections/blob/main/LICENSE"><img alt="License" src="https://img.shields.io/github/license/assegaiphp/collections?style=flat-square"></a>
  <img alt="Status active" src="https://img.shields.io/badge/status-active-10b981?style=flat-square">
</p>

# AssegaiPHP Collections

<p align="center">Typed collection, list, set, queue, and stack primitives for modern PHP applications.</p>

`assegaiphp/collections` provides small in-memory collection types used across the AssegaiPHP ecosystem. Each concrete collection receives an item type and enforces it when values enter the collection.

The package includes:

- `Collection` for general typed collections
- `ItemList` for indexed access, searching, insertion, and removal
- `Set` for unique values and set operations
- `Queue` for first-in, first-out access
- `Stack` for last-in, first-out access

## Requirements

- PHP 8.4 or newer

## Installation

```bash
composer require assegaiphp/collections
```

## Usage

Create a collection by declaring the accepted PHP type:

```php
use Assegai\Collections\Collection;

$numbers = new Collection('integer', [1, 2, 3]);
$numbers->add(4);

$even = $numbers->filter(fn(int $number): bool => $number % 2 === 0);
$doubled = $numbers->map(fn(int $number): int => $number * 2);
$total = $numbers->reduce(fn(int $carry, int $number): int => $carry + $number, 0);
```

Passing a value that does not match the declared type raises a `TypeError`.

`ItemList` adds indexed access and search operations:

```php
use Assegai\Collections\ItemList;

$names = new ItemList('string', ['Ada', 'Grace', 'Margaret']);

echo $names[0];
$index = $names->findIndex(fn(string $name): bool => $name === 'Grace');
```

Class names can be used as the declared type for object collections.

## Contributing

For contribution and pull request conventions, see [Commit and PR Guidelines](./docs/commit-and-pr-guidelines.md).

## License

AssegaiPHP Collections is [MIT licensed](LICENSE).
