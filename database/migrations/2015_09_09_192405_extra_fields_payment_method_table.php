<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraFieldsPaymentMethodTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method', function (Blueprint $table) {
            $table->integer('order_confirmed_order_status_id')->unsigned()->nullable();
            $table->foreign('order_confirmed_order_status_id')->references('id')->on('order_status')->onDelete('set null');
            $table->integer('payment_completed_order_status_id')->unsigned()->nullable();
            $table->foreign('payment_completed_order_status_id')->references('id')->on('order_status')->onDelete('set null');
            $table->integer('payment_failed_order_status_id')->unsigned()->nullable();
            $table->foreign('payment_failed_order_status_id')->references('id')->on('order_status')->onDelete('set null');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
