# Dynamic Log settings for Laravel

With this package you will be able to change log settings in real time.

## Requirements

Laravel 5.

## Installation

You can install the package using the [Composer](https://getcomposer.org/) package manager. You can install it by running this command in your project root:

```sh
composer require rlacerda83/laravel-dynamic-logger
```

## Configuration

Add the `DynamicLogger\DynamicLoggerServiceProvider` provider to the `providers` array in `config/app.php`:

```php
'providers' => [
  DynamicLogger\DynamicLoggerServiceProvider::class,
],
```

Then add the facade to your `aliases` array:

```php
'aliases' => [
  ...
  'DynamicLogger' => DynamicLogger\Facades\DynamicLogger::class,
],
```

## Usage

The DynamicLogger facade is now your interface to the library.

Note that if you're using the facade in a namespace (e.g. `App\Http\Controllers` in Laravel 5) you'll need to either `use DynamicLogger` at the top of your class to import it, or append a backslash to access the root namespace directly when calling methods, e.g. `\DynamicLogger::method()`.

```php
/**
 * Use setHandlers to modify the log settings
 *
 * @param array $handlers - array handlers (monolog)
 * @param bool $logOnlyThisHandlers - If true, it ignores the default handler of laravel and uses only the handlers sent
 * @param bool $cliLogger - future improvement
 */

$file = 'path_to_log/file.log'
$handlers[] = new StreamHandler($file);
\DynamicLogger::setHandlers($handlers, true, $cliLogger);
//From that moment, the log will only be used with informed handlers

// you can use Log::info() or you can use DynamicLogger::info()
/**
 * All log methods in DynamicLogger accept this params
 *
 * @param string $message
 * @param array $params
 * @param array $context
 */
 
 //Both have the same behavior
Log::info('Info message');
DynamicLogger::info('Info Message');

// DynamicLogger with params
DynamicLogger::info(
  'Event %s has been successfully started. Next event will be the %s', [$currentEvent, $nextEvent]);
```
