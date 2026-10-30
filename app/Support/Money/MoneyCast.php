<?php

namespace App\Support\Money;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Stores Money as an integer kobo column.
 *
 * @implements CastsAttributes<Money, Money|int>
 */
final class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Money
    {
        return $value === null ? null : Money::ofKobo((int) $value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        return match (true) {
            $value === null => null,
            $value instanceof Money => $value->kobo,
            is_int($value) => $value,
            default => throw new InvalidArgumentException("[{$key}] must be set with a Money instance or integer kobo."),
        };
    }
}
