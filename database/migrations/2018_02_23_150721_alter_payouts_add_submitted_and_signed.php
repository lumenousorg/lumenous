<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayoutsAddSubmittedAndSigned extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payouts',
                      function (Blueprint $table) {
            $table->boolean('submitted')->default(0)->after('transaction_hash');
            $table->boolean('signed')->default(0)->after('submitted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn(['submitted', 'signed']);
        });
    }

}
