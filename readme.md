# Operators

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require ilbronza/operators
```

## Usage

`OperatorRowAssociatorHelper::associateRowByParameters()` requires an existing
operator/contracttype association by default. To create missing associations while
adding rows, set this option in the project's `config/operators.php`:

```php
'models' => [
    'operatorRow' => [
        'createMissingOperatorContracttype' => true,
    ],
],
```

New associations use the configured `operatorContracttype` model and its normal
events. Only `operator_id` and `contracttype_id` are supplied; costs, levels, and
other optional fields keep their model/database defaults. Soft-deleted
associations are not restored. If creation is disabled, the helper raises a
validation error naming the operator and the role before adding the row.

The existing row flow has no encompassing transaction: unless the caller opens
one, a later failure can leave the new association persisted. The package's
association table also has no unique constraint on the operator/contracttype
pair, so concurrent requests can still create duplicates.

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/ilbronza/operators.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ilbronza/operators.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/ilbronza/operators/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/ilbronza/operators
[link-downloads]: https://packagist.org/packages/ilbronza/operators
[link-travis]: https://travis-ci.org/ilbronza/operators
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/ilbronza
[link-contributors]: ../../contributors
