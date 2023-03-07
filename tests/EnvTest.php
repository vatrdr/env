<?php

declare(strict_types=1);

namespace Tests\Env;

use Dotenv\Dotenv;
use Dotenv\Repository\RepositoryBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use VatRadar\Env\Env;


use function file_put_contents;

/**
 * Yes, I'm aware this is using the dependency with the SUT.
 * No, I don't care.
 * I wasted enough time trying to make the mock doubles work.
 * I got tired of wasting time.
 */
#[CoversClass(Env::class)]
class EnvTest extends TestCase
{
    #[DataProvider('mockDataProvider')]
    public function testEverything($pathOrObj, $repository, ?string $expectedException = null, string $testVar = 'THIS_TEST_ENV'): void
    {
        if ($expectedException) {
            $this->expectException($expectedException);
        }

        Env::init($pathOrObj, $repository);

        static::assertEquals(42, Env::get($testVar));
    }

    public static function mockDataProvider(): array
    {
        $path = __DIR__;
        file_put_contents(__DIR__ . '/.env', 'THIS_TEST_ENV=42');

        $dotEnv = Dotenv::createImmutable($path);
        $repository = RepositoryBuilder::createWithDefaultAdapters()->make();

        return [
            ['pathOrObj' => clone $dotEnv, 'repository' => null, 'expectedException' => null],
            ['pathOrObj' => clone $dotEnv, 'repository' => clone $repository, 'expectedException' => RuntimeException::class],
            ['pathOrObj' => $path, 'repository' => clone $repository, 'expectedException' => null],
            ['pathOrObj' => $path, 'repository' => null, 'expectedException' => null],
            ['pathOrObj' => $path, 'repository' => null, 'expectedException' => RuntimeException::class, 'testVar' => 'NONEXISTENT'],
        ];
    }
}
