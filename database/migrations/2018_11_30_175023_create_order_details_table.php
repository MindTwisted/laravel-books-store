<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('book_title');
            $table->unsignedInteger('book_count');
            $table->decimal('book_price', 8, 2);
            $table->decimal('book_discount', 8, 2)->default('0.00');
            $table->unsignedInteger('book_id')->nullable();
            $table->unsignedInteger('order_id');
            $table->timestamps();

            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('set null');

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details', function (Blueprint $table) {
            $table->dropForeign(['book_id', 'order_id']);
        });
    }
}
