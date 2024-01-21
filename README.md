# WordPress Users API Test Plugin

## Introduction

WordPress Users API Test Plugin is showing users table from API and is made using [inpsyde/modularity](https://github.com/inpsyde/modularity).

inpsyde/modularityis a modular [PSR-11](https://github.com/php-fig/container) implementation for WordPress Plugins,
Themes or Libraries.

## Installation

```
$ git clone https://github.com/mkhvsl/mv-users-api-test.git
$ cd mv-users-api-test
$ composer install
```

## Minimum Requirements and Dependencies

* inpsyde/modularity

When installed for development via Composer, the package also requires:

* inpsyde/php-coding-standards
* phpunit/phpunit
* brain/monkey

## Documentation

Users table is available on visiting https://[wordpress-installation]/mv-users-api-test/. Plugin endpoint can be changed in plugins settings.

Requests to an API are cached with WordPress transients. 

To run phpunit tests use:

```
$ vendor/bin/phpunit
```

To run coding standards tests use:

```
$ vendor/bin/phpcs 
```

## License

Copyright (c) Mykhailo Vasylenko

This code is licensed under the [GPLv2+ License](LICENSE).