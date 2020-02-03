<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type');
            $table->string('serial_number');
            $table->string('IMEI_number');
            $table->string('phone_number');
            $table->integer('connection_method_id')->unsigned();
            $table->integer('ports')->unsigned();
            $table->integer('installed_place_id')->unsigned();
            $table->timestamp('installed_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('place');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->text('comments')->nullable();
            $table->string('installed_by');
            $table->string('installation_photo');
            $table->integer('purchase_type_id')->unsigned();
            $table->bigInteger('owner_id')->unsigned();
            $table->integer('billing_category_id')->unsigned();
            $table->double('hour_cost')->nullable()->default(null);
            $table->double('service_cost')->nullable()->default(null);
            $table->double('owner_cost')->nullable()->default(null);
            $table->text('charging_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
