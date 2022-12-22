<?php

declare(strict_types=1);

namespace Flatter\Tests;

use Flatter\Flatter;

class WithCustomSeparatorTest extends AbstractTest
{
    /**
     * {@inheritDoc}
     *
     * @return array<string, array<int, array<string, array<string, array<string, int>|string>|int|string>>>
     */
    public function provideFlatten(): array
    {
        return [
            'Flattened with "#"' => [
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
                    'array#key'                  => 'value',
                    'array#inner#key'            => 1,
                    'array#inner#with_separator' => 2,
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
            'Inflated with "#"' => [
                [
                    'array#key'                  => 'value',
                    'array#inner#key'            => 1,
                    'array#inner#with_separator' => 2,
                ],
                [
                    'array' => [
                        'key'   => 'value',
                        'inner' => [
                            'key'            => 1,
                            'with_separator' => 2,
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
        return (new Flatter($data))->withCompositeKeySeparator('#');
    }
}
