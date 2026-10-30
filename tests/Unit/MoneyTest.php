<?php

use App\Support\Money\Money;

it('parses naira strings without floating point', function (string $input, int $kobo) {
    expect(Money::fromNaira($input)->kobo)->toBe($kobo);
})->with([
    ['1500', 150000],
    ['1500.5', 150050],
    ['1,500.50', 150050],
    ['0.07', 7],
    ['-20.10', -2010],
]);

it('rejects more than two decimal places', function () {
    Money::fromNaira('10.005');
})->throws(InvalidArgumentException::class);

it('formats kobo as naira', function () {
    expect(Money::ofKobo(150050)->toNaira())->toBe('1500.50')
        ->and(Money::ofKobo(-7)->toNaira())->toBe('-0.07');
});
