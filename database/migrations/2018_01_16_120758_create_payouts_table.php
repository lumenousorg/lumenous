<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts',
                       function (Blueprint $table) {
            $table->increments('id');
            $table->string('total_payout_amount');
            $table->string('transaction_fee');
            $table->string('account_payout_amount');
            $table->string('charity_payout_amount');
            $table->string('transaction_hash')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('inflation_effect_id')->unsigned()->index();
            $table->foreign('inflation_effect_id')->references('id')->on('inflation_effects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payouts');
    }

}
