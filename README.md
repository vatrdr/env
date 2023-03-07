# Env Convenience Helper

This package is fairly simple - it's meant to standardize access to environment variables without having
to resort to accessing `$_ENV['SOME_VAR']` directly. Yes, this is more of a preference thing rather than
actually addressing a technical deficiency. And that's okay.

It encapsulates [vlucas/phpdotenv][1] package and provides access to that
package's instance for custom configuration as well as a convenience method to access environment variables.
It is also opinionated, so it initializes as immutable by default. It is also not meant for super-complex
configurations with multiple .env files. For that, just use [dotenv][1] directly. 

## Quick install

```shell
composer require vatradar/env
```

Env attempts to follow [semantic versioning][2] principles but is also part of a larger private
monorepo and shared codebase – due to this, version bumps of this package may happen even if no package-specific 
changes occur.

## Quick Start

```php
<?php

use VatRadar\Env\Env;

require __DIR__.'/vendor/autoload.php';

// Can be used without initializing to grab normally-existing environment vars
Env::get('USER');

// Initialize using path to directory that will contain your .env file
// and then access environment variables
Env::init(__DIR__);
$myVar = Env::get('MY_ENV_VAR');

// Pass a repository if needed
$repository = Dotenv\Repository\RepositoryBuilder::createWithDefaultAdapters()
    ->allowList(['FOO', 'BAR'])
    ->make();
Env::init(__DIR__, $repository);

// Customize dotenv configuration
$dotenv = Env::create(__DIR__);
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
Env::init($dotenv);
$dbHost = Env::get('DB_HOST');

// You can also add the repository
$dotenv = Env::create(__DIR__, $repository);
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
Env::init($dotenv);

```

[1]: https://github.com/vlucas/phpdotenv
[2]: https://semver.org
