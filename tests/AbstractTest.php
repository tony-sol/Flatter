<?php

declare(strict_types=1);

namespace Flatter\Tests;

use Flatter\Flatter;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    /**
     * Data provider of flattened data
     *
     * @return array<string, array<int, array<string, array<string, array<string, int>|string>|int|string>>>
     */
    abstract public function provideFlatten(): array;

    /**
     * Data provider of inflated data
     *
     * @return array<string, array<int, array<string, array<string, array<string, int>|string>|int|string>>>
     */
    abstract public function provideInflate(): array;

    /**
     * Build Flatter object
     *
     * @param array<string, mixed> $data Initial data.
     *
     * @return Flatter
     */
    abstract protected function flatter(array $data): Flatter;

    /**
     * Test common flatten scenario
     *
     * @param array<string, array<string, mixed>> $data      Initial data.
     * @param array<string, mixed>                $assertion Flattened data.
     *
     * @dataProvider provideFlatten
     *
     * @return void
     */
    public function testFlatten(array $data, array $assertion): void
    {
        self::assertEquals($assertion, $this->flatter($data)->flatten());
    }

    /**
     * Test common inflate scenario
     *
     * @param array<string, mixed>                $data      Initial data.
     * @param array<string, array<string, mixed>> $assertion Inflated data.
     *
     * @dataProvider provideInflate
     *
     * @return void
     */
    public function testInflate(array $data, array $assertion): void
    {
        self::assertEquals($assertion, $this->flatter($data)->inflate());
    }
}
