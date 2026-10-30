<?php

/*
 * ADR-004 promoted from prose to an executable check.
 * The decision record keeps the *why*; this file keeps the *what*.
 */

$modules = ['Customers', 'Catalog', 'Orders', 'Billing'];

foreach ($modules as $module) {
    $others = array_values(array_diff($modules, [$module]));

    arch("{$module} only reaches other modules through their Contracts")
        ->expect("App\\Modules\\{$module}")
        ->not->toUse(array_merge(
            array_map(fn ($m) => "App\\Modules\\{$m}\\Models", $others),
            array_map(fn ($m) => "App\\Modules\\{$m}\\Internal", $others),
        ));
}
