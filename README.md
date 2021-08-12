# Log Component

[![Latest Stable Version](https://img.shields.io/packagist/v/pollen-solutions/log.svg?style=for-the-badge)](https://packagist.org/packages/pollen-solutions/log)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE.md)
[![PHP Supported Versions](https://img.shields.io/badge/PHP->=7.4-8892BF?style=for-the-badge&logo=php)](https://www.php.net/supported-versions.php)

Pollen Solutions **Log** Component provides a PSR-3 logging implementation based on Monolog.

## Installation

```bash
composer require pollen-solutions/log
```

## Basic Usage

```php
use Pollen\Log\LogManager;

$log = new LogManager();

// Adds a log record at the DEBUG level
$log->debug('My debug message');

// Adds a log record at the INFO level
$log->debug('My info message');

// Adds a log record at the SUCCESS level
$log->success('My success message');

// Adds a log record at the NOTICE level
$log->notice('My notice message');

// Adds a log record at the WARNING level
$log->warning('My warning message');

// Adds a log record at the ERROR level
$log->error('My error message');

// Adds a log record at the CRITICAL level
$log->critical('My critical message');

// Adds a log record at the ALERT level
$log->alert('My alert message');

// Adds a log record at the EMERGENCY level
$log->emergency('My alert message');
 
```

## Log storage path

By default, if none specific handler is specified, the log manager uses the Monolog RotatingFileHandler. It stores a log
file in the app public dir. It is recommended to change it to a more suitable path with appropriate write file
permissions.

```php
use Pollen\Log\LogManager;

$log = (new LogManager())->setDefaultStoragePath('/var/log/myapp');
```

## Create a built-in channel and use it

Like the default log channel, the built-in channel uses the RotatingFileHandler and LineFormatter.
But you can customizes some of its parameters. 

```php
use Pollen\Log\Logger;
use Pollen\Log\LogManager;

$log = new LogManager();

$log->registerChannel('my-channel', [
       /** 
        * Log filename (relative or absolute).
        * @see \Monolog\Handler\RotatingFileHandler
        * @var string|null
        */
        'filename' => null,
       /** 
        * Rotation frequency.
        * @see \Monolog\Handler\RotatingFileHandler
        * @var int|null
        */
       'rotate' => null,
       /** 
        * Minimum logging level.
        * @see \Monolog\Handler\RotatingFileHandler
        * @var string|null
        */
        'level'  => Logger::SUCCESS,
       /** 
        * Line format.
        * @see \Monolog\Formatter\LineFormatter
        * @var string|null
        */
      'format' => null,
       /** 
        * Date format. 
        * @see \Monolog\Formatter\LineFormatter
        * @var string|null
        */
      'date_format' => null
]);

$log->channel('my-channel')->success('Test log success message');
```

## Create your own custom channel

Monolog purposes a large variety 
of [handlers](https://github.com/Seldaek/monolog/blob/main/doc/02-handlers-formatters-processors.md#handlers)
and [formatters](https://github.com/Seldaek/monolog/blob/main/doc/02-handlers-formatters-processors.md#formatters). You
might need to use your own suit and use it in Pollen Solutions Log Component.

```php
use Monolog\Handler\NativeMailerHandler;
use Pollen\Log\Logger;
use Pollen\Log\LogManager;

$log = new LogManager();

$channel = new Logger(
    'mailer',
    [new NativeMailerHandler('to@domain.ltd', 'You have a log report message !', 'from@domain.ltd')]
);

$log->addChannel($channel);

$log->channel('mailer')->error('Test log error message');
```