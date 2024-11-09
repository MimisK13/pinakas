# Pinakas

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [CONTRIBUTING.md](CONTRIBUTING.md) to see a to do list.

## Installation

Via Composer

```bash
composer require mimisk13/pinakas
```

## Usage

```php
    $table = (new Pinakas())
        ->model(User::class)
        ->columns([
            Column::make('Id', 'id'),
            Column::make('Name', 'name'),
            Column::make('Email', 'email'),
            Column::make('Created At', 'created_at'),
        ])
        ->actions([
            // SINGLE ACTIONS
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),

            // GROUP
            ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
        ])
        ->bulkActions([
            DeleteAction::make(),
        ]);
```

## Change log

Please see the [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details and a todolist.

## Security

If you discover any security related issues, please email `mimisk88@gmail.com` instead of using the issue tracker.

## Credits

- [Mimis K][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [LICENSE file](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/mimisk13/pinakas.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mimisk13/pinakas.svg?style=flat-square
[ico-styleci]: https://github.styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/mimisk13/pinakas
[link-downloads]: https://packagist.org/packages/mimisk13/pinakas
[link-styleci]: https://github.styleci.io/repos/12345678
[link-author]: https://github.com/mimisk13
[link-contributors]: ../../contributors
