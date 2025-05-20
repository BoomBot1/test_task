<?php

describe('globals', function () {
    test('application does not use dd or dump')
        ->expect(['dd', 'dump'])
        ->not->toBeUsed();

    test('application does not use env')
        ->expect(['env'])
        ->not->toBeUsed();

    test('controllers has suffix Controller')
        ->expect('App\Http\Controllers')
        ->toHaveSuffix('Controller')
        ->toExtend(\App\Http\Controllers\Controller::class);

    test('requests has suffix Request')
        ->expect('App\Http\Requests')
        ->toHaveSuffix('Request');

    test('repositories has suffix Repository')
        ->expect('App\Repositories')
        ->toHaveSuffix('Repository');

    test('services has suffix Service')
        ->expect('App\Services')
        ->toHaveSuffix('Service');

    test('DTOs has suffix DTO')
        ->expect('App\DTOs')
        ->toHaveSuffix('DTO');

    test('DTOs is readonly')
        ->expect('App\DTOs')
        ->toBeReadonly();
});
