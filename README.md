# CrudModels

[![Build Status](https://travis-ci.org/scootersam/crud-models.svg?branch=master)](https://travis-ci.org/scootersam/crud-models)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/scootersam/crud-models/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/scootersam/crud-models/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/scootersam/crud-models/badge.svg?branch=master)](https://coveralls.io/github/scootersam/crud-models?branch=master)

[![Packagist](https://img.shields.io/packagist/v/scootersam/crud-models.svg)](https://packagist.org/packages/scootersam/crud-models)
[![Packagist](https://poser.pugx.org/scootersam/crud-models/d/total.svg)](https://packagist.org/packages/scootersam/crud-models)
[![Packagist](https://img.shields.io/packagist/l/scootersam/crud-models.svg)](https://packagist.org/packages/scootersam/crud-models)

Package description: CHANGE ME

## Installation

Install via composer
```bash
composer require scootersam/crud-models
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
ScooterSam\CrudModels\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
ScooterSam\CrudModels\Facades\CrudModels::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="ScooterSam\CrudModels\ServiceProvider" --tag="config"
```

## Usage

CHANGE ME

## Security

If you discover any security related issues, please email sam@idevelopthings.com
instead of using the issue tracker.

## Credits

- [Sam Parton](https://github.com/scootersam/crud-models)
- [All contributors](https://github.com/scootersam/crud-models/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
