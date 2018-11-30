<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', ['in_process', 'done'])->default('in_process');
            $table->decimal('total_discount', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('payment_type_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('payment_type_id')
                ->references('id')
                ->on('payment_types')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'payment_type_id']);
        });
    }
}
