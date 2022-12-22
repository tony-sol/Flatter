<?php

declare(strict_types=1);

namespace Flatter\Tests;

use Flatter\Flatter;

class WithClosureOnKeys extends AbstractTest
{
    /**
     * {@inheritDoc}
     *
     * @return array<string, array<int, array<string, array<string, array<string, int>|string>|int|string>>>
     */
    public function provideFlatten(): array
    {
        return [
            'Flattened with transform keys to uppercase' => [
                [
                    'array' => [
                        'key'   => 'value',
                        'inner' => [
                            'key'            => 1,
                            'with_separator' => 2,
                        ],
                    ],
                ],
                [
                    'ARRAY_KEY'                  => 'value',
                    'ARRAY_INNER_KEY'            => 1,
                    'ARRAY_INNER_WITH_SEPARATOR' => 2,
                ],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, array<int, array<string, array<string, array<string, int>|string>|int|string>>>
     */
    public function provideInflate(): array
    {
        return [
            'Inflated with transform keys to uppercase' => [
                [
                    'array_key'                  => 'value',
                    'array_inner_key'            => 1,
                    'array_inner_with_separator' => 2,
                ],
                [
                    'ARRAY' => [
                        'KEY'   => 'value',
                        'INNER' => [
                            'KEY'  => 1,
                            'WITH' => ['SEPARATOR' => 2],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed> $data Initial data.
     *
     * @return Flatter
     */
    protected function flatter(array $data): Flatter
    {
        return (new Flatter($data))->applyClosureToKeys(static fn(string $key): string => strtoupper($key));
    }
}
