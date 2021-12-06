<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->unsignedInteger('recipient_user_id')->nullable();
            $table->string('token')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('amount',9,3)->nullable();
            $table->unsignedInteger('transactionable_id')->nullable();
            $table->string('transactionable_type')->nullable();
            $table->string('ip')->nullable();
            $table->string('payment_method_key')->nullable();
            $table->string('payment_method_type')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
