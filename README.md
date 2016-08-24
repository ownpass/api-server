# OwnPass

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

The OwnPass server application. This application provides an API as well
as an interface for the accounts to manage their credentials.

## Installation

### From source (command line)

1. Clone the repository to a directory that is accessible by your webserver: `git clone git@github.com:ownpass/ownpass.git`
2. Enter the directory: `cd ownpass`
3. Install composer dependencies: `composer install --no-dev -o`
4. Run `php public/index.php ownpass:install` to finish the setup.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## License

All rights reserved. The application is free to use but the rights of the source code are with the OwnPass team.

[ico-version]: https://img.shields.io/packagist/v/ownpass/ownpass.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/ownpass/ownpass/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/ownpass/ownpass.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/ownpass/ownpass.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ownpass/ownpass
[link-travis]: https://travis-ci.org/ownpass/ownpass
[link-scrutinizer]: https://scrutinizer-ci.com/g/ownpass/ownpass/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/ownpass/ownpass
[link-contributors]: ../../contributors
