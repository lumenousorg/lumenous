<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersAddLmnryVerify extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users',
                      function (Blueprint $table) {
            $table->string('lmnry_verify_key')->nullable()->after('remember_token');
            $table->boolean('lmnry_verified')->default(0)->after('lmnry_verify_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lmnry_verify_key', 'lmnry_verified']);
        });
    }

}
