<?php

use Faker\Generator as Faker;
use lumenous\Services\StellarService;

/*
  |--------------------------------------------------------------------------
  | Model Factories
  |--------------------------------------------------------------------------
  |
  | This directory should contain each of the model factory definitions for
  | your application. Factories provide a convenient way to generate new
  | model instances for testing / seeding your application's database.
  |
 */

$stellarService = app()->make(StellarService::class);

$factory->define(lumenous\User::class,
                 function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123456'),
        'remember_token' => str_random(10),
        'stellar_public_key' => str_random(56),
        'is_verified' => 1,
        'lmnry_verified' => 1
    ];
});

$factory->state(lumenous\User::class, 'real-stellar-key',
                function (Faker $faker) use ($stellarService) {
    $stellarAccount = $stellarService->createAccount();
    return [
        'stellar_public_key' => $stellarAccount['publicKey'],
    ];
});
