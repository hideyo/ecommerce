<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InvoiceTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price_with_tax', 12, 4)->nullable();
            $table->decimal('price_without_tax', 12, 4)->nullable();
            $table->decimal('total_discount', 12, 4)->nullable();
            $table->enum('type', array('debit', 'credit'))->default('debit');
            $table->integer('generated_year_invoice_id');
            $table->index('generated_year_invoice_id');
            $table->string('generated_custom_invoice_id')->nullable()->unique();
            $table->index('generated_custom_invoice_id');
            $table->integer('order_id')->unsigned()->unique()->nullable();
            $table->foreign('order_id')->references('id')->on('order')->onDelete('set null');
            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('client')->onDelete('set null');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
                        
            $table->timestamps();
        });


        Schema::create('invoice_rule', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', array('product', 'sending_cost', 'payment_cost', 'extra'));
            $table->string('title');
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('weight')->nullable();
            $table->decimal('price_with_tax', 12, 4)->nullable();
            $table->decimal('price_without_tax', 12, 4)->nullable();
            $table->decimal('total_price_with_tax', 12, 4)->nullable();
            $table->decimal('total_price_without_tax', 12, 4)->nullable();  
            $table->decimal('tax_rate', 12, 4)->nullable();                      
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoice')->onDelete('cascade');
            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('product')->onDelete('set null');
            $table->integer('tax_rate_id')->unsigned()->nullable();
            $table->foreign('tax_rate_id')->references('id')->on('tax_rate')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('invoice_address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company')->nullable();
            $table->enum('gender', array('male', 'female'));
            $table->string('initials')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('street')->nullable();
            $table->bigInteger('housenumber')->nullable();
            $table->string('housenumber_suffix')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->bigInteger('phone')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->string('email')->nullable();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoice')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
        });

        Schema::table('invoice', function (Blueprint $table) {
            $table->integer('delivery_invoice_address_id')->unsigned()->nullable();
            $table->foreign('delivery_invoice_address_id', 'i_delivery_invoice_address_id_fk')->references('id')->on('invoice_address')->onDelete('set null');
            $table->integer('bill_invoice_address_id')->unsigned()->nullable();
            $table->foreign('bill_invoice_address_id', 'i_bill_invoice_address_id_fk')->references('id')->on('invoice_address')->onDelete('set null');
        });

        Schema::table('invoice_rule', function (Blueprint $table) {
            $table->string('reference_code')->nullable();
            $table->string('product_attribute_title')->nullable();
            $table->integer('product_attribute_id')->unsigned()->nullable();
            $table->foreign('product_attribute_id', 'ir_product_attribute_id_fk')->references('id')->on('product_attribute')->onDelete('set null');
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
