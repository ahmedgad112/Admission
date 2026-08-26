<?php

use Illuminate\Database\Schema\Builder;
use Tests\TestCase;

uses(TestCase::class);

test('default string columns stay within older mysql index limits', function () {
    expect(Builder::$defaultStringLength)->toBe(191);
});

test('mysql connections use innodb so foreign keys are supported', function () {
    expect(config('database.connections.mysql.engine'))->toBe('InnoDB')
        ->and(config('database.connections.mariadb.engine'))->toBe('InnoDB');
});
