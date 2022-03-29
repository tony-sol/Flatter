<?php

declare(strict_types=1);

namespace Flatter;

class Flatter
{
    private string $compositeKeySeparator = '_';

    private bool $needToEscapeSeparatorInKeys = false;

    private \Closure $closureOnKeys;

    private \Closure $closureOnValues;

    /**
     * Flatten constructor
     *
     * @param array $data Base data to flatten or inflate.
     */
    public function __construct(
        private array $data,
    ) {
        $this->closureOnKeys   = static fn (mixed $key): mixed => $key;
        $this->closureOnValues = static fn (mixed $value): mixed => $value;
    }

    /**
     * Use passed string as key separator
     *
     * @param string $separator String to use as key separator.
     *
     * @return static
     */
    public function withCompositeKeySeparator(string $separator): self
    {
        $this->compositeKeySeparator = $separator;
        return $this;
    }

    /**
     * Should string suggest as separator being escaped in common keys
     *
     * @param boolean $needEscaping True if separator should be escaped.
     *
     * @return static
     */
    public function escapingSeparatorInKeys(bool $needEscaping = true): self
    {
        $this->needToEscapeSeparatorInKeys = $needEscaping;
        return $this;
    }

    /**
     * Closure to apply to all array keys
     *
     * @param \Closure $onKey Closure to apply to all array keys.
     *
     * @return static
     */
    public function applyClosureToKeys(\Closure $onKey): self
    {
        $this->closureOnKeys = $onKey;
        return $this;
    }

    /**
     * Closure to apply to all array scalar values
     *
     * @param \Closure $onValue Closure to apply to all array scalar values.
     *
     * @return static
     */
    public function applyClosureToValues(\Closure $onValue): self
    {
        $this->closureOnValues = $onValue;
        return $this;
    }

    /**
     * Flatten array using built rules
     *
     * @return array<array-key, mixed>
     */
    public function flatten(): array
    {
        $flatter = function (array $data, string $prefix = '') use (&$flatter): array {
            $onValue = $this->closureOnValues;
            $onKey   = $this->closureOnKeys;
            $result  = [];
            /**
             * @var string|integer|float|array-key $key
             * @var array|mixed $value
             */
            foreach ($data as $key => $value) {
                $key = $this->escapeKey($key);
                if (\is_array($value)) {
                    /**
                     * @var            array<array-key, mixed> $tempResult
                     * @psalm-suppress MixedFunctionCall
                     */
                    $tempResult = $flatter($value, "{$prefix}{$key}{$this->compositeKeySeparator}");
                    $result     = \array_merge($result, $tempResult);
                } else {
                    /** @psalm-suppress MixedAssignment */
                    $result[(string)$onKey("{$prefix}{$key}")] = $onValue($value);
                }
            }
            /** @var array<array-key, mixed> $result */
            return $result;
        };
        return $flatter($this->data);
    }

    /**
     * Inflate array using built rules
     *
     * @return array
     */
    public function inflate(): array
    {
        $onValue = $this->closureOnValues;
        $onKey   = $this->closureOnKeys;
        $result  = [];
        /**
         * @var string $flattenedKey
         * @var mixed $value
         */
        foreach ($this->data as $flattenedKey => $value) {
            $keys         = $this->splitKey($flattenedKey);
            $reversedKeys = array_reverse($keys);
            /** @var mixed $tempData */
            $tempData = $onValue($value);
            foreach ($reversedKeys as $key) {
                $processedKey = (string)$onKey($key);
                $tempData     = [$this->unescapeKey($processedKey) => $tempData];
            }
            /** @var array $tempData */
            $result[] = $tempData;
        }
        return array_merge_recursive([], ...$result);
    }

    /**
     * Prepare key to use in flatten array
     *
     * @param string|integer|float $key Key to prepare.
     *
     * @return string|integer|float
     */
    private function escapeKey(string|int|float $key): string|int|float
    {
        return $this->needToEscapeSeparatorInKeys
            ? addcslashes((string)$key, $this->compositeKeySeparator)
            : $key;
    }

    /**
     * Unescape key after splitter to inflate array
     *
     * @param string $key Key to unescape.
     *
     * @return string
     */
    private function unescapeKey(string $key): string
    {
        return $this->needToEscapeSeparatorInKeys
            ? stripcslashes($key)
            : $key;
    }

    /**
     * Split key to inflate
     *
     * @param string $key Combined array key.
     *
     * @return string[]
     */
    private function splitKey(string $key): array
    {
        return $this->needToEscapeSeparatorInKeys
            ? preg_split("/(?<!\\\)\\{$this->compositeKeySeparator}/ui", $key)
            : explode($this->compositeKeySeparator, $key);
    }
}
