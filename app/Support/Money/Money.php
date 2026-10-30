<?php

namespace App\Support\Money;

use InvalidArgumentException;
use JsonSerializable;

/**
 * An amount of Nigerian naira held as an integer number of kobo.
 */
final class Money implements JsonSerializable
{
    private function __construct(public readonly int $kobo)
    {
    }

    public static function ofKobo(int $kobo): self
    {
        return new self($kobo);
    }

    public static function zero(): self
    {
        return new self(0);
    }

    /**
     * Parse a naira amount such as "1500", "1500.5" or "1,500.50" without
     * going through floating point.
     */
    public static function fromNaira(string|int $naira): self
    {
        $value = str_replace([',', ' '], '', (string) $naira);

        if (! preg_match('/^(-)?(\d+)(?:\.(\d{1,2}))?$/', $value, $m)) {
            throw new InvalidArgumentException("Invalid naira amount [{$naira}].");
        }

        $kobo = ((int) $m[2]) * 100 + (int) str_pad($m[3] ?? '0', 2, '0');

        return new self($m[1] === '-' ? -$kobo : $kobo);
    }

    public function plus(self $other): self
    {
        return new self($this->kobo + $other->kobo);
    }

    public function minus(self $other): self
    {
        return new self($this->kobo - $other->kobo);
    }

    public function times(int $quantity): self
    {
        return new self($this->kobo * $quantity);
    }

    public function isNegative(): bool
    {
        return $this->kobo < 0;
    }

    public function toNaira(): string
    {
        $sign = $this->kobo < 0 ? '-' : '';
        $abs = abs($this->kobo);

        return sprintf('%s%d.%02d', $sign, intdiv($abs, 100), $abs % 100);
    }

    public function jsonSerialize(): string
    {
        return $this->toNaira();
    }
}
