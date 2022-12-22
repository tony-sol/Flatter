<?php

declare(strict_types=1);

namespace Flatter\Tests;

use Flatter\Flatter;

class CompositeTest extends AbstractTest
{
    /**
     * {@inheritDoc}
     *
     * @return array<string, array<int, array<string, array<string, array<string, int>|string>|int|string>>>
     */
    public function provideFlatten(): array
    {
        return [
            'Implode users data into combined dataset' => [
                [
                    'BOB'   => [
                        'Age'    => 28,
                        'Height' => '6\'4"',
                        'Weight' => 89.1,
                    ],
                    'aLiCe' => [
                        'Age'    => 25,
                        'Height' => '5\'5"',
                        'Weight' => 42.8,
                    ],
                ],
                [
                    'Bob|>age'      => '@<28>',
                    'Bob|>height'   => '@<6\'4">',
                    'Bob|>weight'   => '@<90>',
                    'Alice|>age'    => '@<25>',
                    'Alice|>height' => '@<5\'5">',
                    'Alice|>weight' => '@<43>',
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
            'Explode users data from combined dataset' => [
                [
                    'Bob|>age'      => '@<28>',
                    'Bob|>height'   => '@<6\'4">',
                    'Bob|>weight'   => '@<90>',
                    'Alice|>age'    => '@<25>',
                    'Alice|>height' => '@<5\'5">',
                    'Alice|>weight' => '@<43>',
                ],
                [
                    'Bob'   => [
                        'Age'    => 28,
                        'Height' => '6\'4"',
                        'Weight' => 90,
                    ],
                    'Alice' => [
                        'Age'    => 25,
                        'Height' => '5\'5"',
                        'Weight' => 43,
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
        return (new Flatter($data))
            ->applyClosureToKeys(static fn(string $key): string => ucfirst(strtolower($key)))
            ->applyClosureToValues(
                static function (string|float|int $value): string {
                    if (is_string($value) && str_starts_with($value, '@<')) {
                        $value = ltrim($value, '@<');
                        $value = rtrim($value, '>');
                        return $value;
                    }
                    $value = is_float($value) ? ceil($value) : $value;
                    return "@<{$value}>";
                }
            )
            ->withCompositeKeySeparator('|>');
    }
}
