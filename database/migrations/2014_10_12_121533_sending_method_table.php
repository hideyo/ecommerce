<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SendingMethodTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sending_method', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->string('title');
            $table->decimal('price', 12, 4)->nullable();
            $table->decimal('no_price_from', 12, 4)->nullable();
            $table->decimal('minimal_weight', 12, 4)->nullable();
            $table->decimal('maximal_weight', 12, 4)->nullable();
            $table->integer('tax_rate_id')->unsigned()->nullable();
            $table->foreign('tax_rate_id')->references('id')->on('tax_rate')->onDelete('set null');
            $table->enum('total_price_discount_type', array('amount', 'percent'))->nullable();
            $table->decimal('total_price_discount_value', 12, 4)->nullable();
            $table->date('total_price_discount_start_date')->nullable();
            $table->date('total_price_discount_end_date')->nullable();
            
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();

            $table->unique(array('title','shop_id'), 'unique_sending_method_title');
        });


        Schema::create('sending_payment_method_related', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sending_method_id')->unsigned();
            $table->foreign('sending_method_id', 'spmr_sending_method_id_fk')->references('id')->on('sending_method')->onDelete('cascade');
            $table->integer('payment_method_id')->unsigned();
            $table->foreign('payment_method_id', 'spmr_payment_method_id_fk')->references('id')->on('payment_method')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id', 'spmr_modified_by_user_id_fk')->references('id')->on('user')->onDelete('set null');         
            $table->timestamps();

            $table->unique(array('sending_method_id','payment_method_id'), 'unique_sending_payment_method_related');
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
