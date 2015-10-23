# Piwik Handler for Monolog

Provides a handler for [Monolog](https://github.com/Seldaek/monolog) that sends records to piwik server.

## Installation

This library can be installed via composer: "piotr-cz/monolog-piwik-handler": "~1.0"

```sh
$ composer require piotr-cz/monolog-piwik-handler
```

## Example use

Example setup using [Pimple](http://pimple.sensiolabs.org/) DI Container

```php
// Add PiwikTracker to DIC
$container['PiwikTracker'] = function($c) {
    return new \PiwikTracker(
        // idSite
        1,
        // apiUrl
        'http://piwik.domain.tld/'
    );
}

// Add Logger to DIC
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('app']);

    $logger->pushHandler(
        new \PiotrCz\MonologPiwikHandler\PiwikHandler(
            // Piwik tracker instance
            $c['PiwikTracker'],
            // Optional category to log errors to.
            'Errors'
        )
    );
}
```

Then in your error handler

```php
$logger->addError('500', ['exception' => $exception]);
```

## Requirements

 * PHP 5.3+

## License

Released under the [MIT License](./LICENSE.md)
