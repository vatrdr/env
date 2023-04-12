<?php

declare(strict_types=1);

namespace VatRadar\Env;

use Dotenv\Dotenv;
use Dotenv\Repository\RepositoryBuilder;
use Dotenv\Repository\RepositoryInterface;
use RuntimeException;

class Env
{
    public static function init(string|Dotenv $pathOrObj, RepositoryInterface $repository = null): void
    {
        if ($pathOrObj instanceof Dotenv) {
            if ($repository !== null) {
                throw new RuntimeException('Dotenv already initialized, repository unusable');
            }

            $pathOrObj->safeLoad();
        } else {
            $dotenv = static::create($pathOrObj, $repository);
            $dotenv->safeLoad();
        }
    }

    public static function create(string $path, ?RepositoryInterface $repository = null): Dotenv
    {
        if ($repository === null) {
            $repository = RepositoryBuilder::createWithDefaultAdapters()->immutable()->make();
        }

        return Dotenv::create($repository, $path);
    }

    public static function get(string $var): ?string
    {
        return $_ENV[$var] ?? null;
    }
}
