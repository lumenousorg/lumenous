<?php

use Faker\Generator as Faker;
use lumenous\Models\InflationEffect;

$factory->define(InflationEffect::class,
                 function (Faker $faker) {
    return [
        'effect_id' => '0032129086973292545-0000000001',
        'amount' => '4925654.5101338',
        'data' => '{"id": "0032129086973292545-0000000001", "type": "account_credited", "_links": {"precedes": {"href": "https://horizon-testnet.stellar.org/effects?order=asc&cursor=32129086973292545-1"}, "succeeds": {"href": "https://horizon-testnet.stellar.org/effects?order=desc&cursor=32129086973292545-1"}, "operation": {"href": "https://horizon-testnet.stellar.org/operations/32129086973292545"}}, "amount": "4925654.5101338", "type_i": 2, "account": "GA5KRSGSE6CYKUIXZ73AWG6SHLYSSBELUTMND54SEARUUB2PA2ZTNA2Y", "asset_type": "native", "paging_token": "32129086973292545-1"}'
    ];
});
