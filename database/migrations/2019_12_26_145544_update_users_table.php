<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('identity_document')->after('email');
            $table->string('avatar')->after('email')->nullable()->default(null);
            $table->double('balance')->default(0)->after('email');
            $table->boolean('verified')->default(0)->after('balance');
            $table->string('rfid')->after('id')->nullable()->default(null);
            $table->string('phone')->after('email');
            $table->string('address')->after('phone')->nullable()->default(null);
            $table->timestamp("last_online_at")->useCurrent();
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
            //
        });
    }
}
