<?php

use Faker\Generator as Faker;
use lumenous\Models\ActiveAccount;
use lumenous\User;

$factory->define(ActiveAccount::class,
                 function (Faker $faker) {
    return [
        'balance' => $faker->randomNumber(9),
        'user_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];
});

$factory->state(ActiveAccount::class, 'real-stellar-account',
                function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->states('real-stellar-key')->create()->id;
        }
    ];
});
