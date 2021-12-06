<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodNanoAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method_nano_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('payment_method_id');
            $table->string('nano_address')->unique();
            $table->string('nano_index');
            $table->dateTime('last_balance_check_at')->nullable();
            $table->dateTime('last_used_at')->nullable();
            $table->dateTime('last_consolidated_at')->nullable();
            $table->text('data')->nullable();
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
        Schema::dropIfExists('payment_method_nano_addresses');
    }
}
