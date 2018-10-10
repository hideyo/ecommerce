<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderTable extends Migration
{

    /**
     * Make changes to the table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('validated')->default(false);
            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('client')->onDelete('set null');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->decimal('price_with_tax', 12, 4)->nullable();
            $table->decimal('price_without_tax', 12, 4)->nullable();
            $table->decimal('total_discount', 12, 4)->nullable();

            $table->integer('generated_year_order_id');
            $table->index('generated_year_order_id');
            $table->string('generated_custom_order_id')->nullable()->unique();
            $table->index('generated_custom_order_id');
            $table->string('coupon_group_title')->nullable();
            $table->string('coupon_type')->nullable();
            $table->string('coupon_discount_way')->nullable();
            $table->string('coupon_title')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('coupon_value', 12, 4)->nullable();
            $table->integer('coupon_id')->nullable()->unsigned();
            $table->foreign('coupon_id')->references('id')->on('coupon')->onDelete('set null');
            $table->longText('browser_detect')->nullable();            

            $table->string('mollie_payment_id')->nullable();
            $table->string('present_gender')->nullable();
            $table->string('present_occassion')->nullable();
            $table->text('present_message')->nullable();  
            $table->text('comments')->nullable();

            $table->timestamps();
        });

        Schema::create('order_status_email_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subject');
            $table->text('content');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();

            $table->unique(array('title','shop_id'), 'unique_email_template_title');
        });


        Schema::create('order_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('color');
            $table->boolean('order_is_validated')->default(false);
            $table->boolean('order_is_paid')->default(false);
            $table->boolean('order_is_delivered')->default(false);
            $table->boolean('send_email_to_customer')->default(false);
            $table->boolean('attach_invoice_to_email')->default(false);
            $table->boolean('attach_order_to_email')->default(false);
            $table->boolean('count_as_revenue')->default(false);
            $table->string('send_email_copy_to')->nullable();
            
            $table->integer('order_status_email_template_id')->unsigned()->nullable();
            $table->foreign('order_status_email_template_id')->references('id')->on('order_status_email_template')->onDelete('set null');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();


            $table->unique(array('title','shop_id'), 'unique_order_status_title');
        });

        Schema::table('order', function (Blueprint $table) {
            $table->integer('order_status_id')->unsigned()->nullable();
            $table->foreign('order_status_id')->references('id')->on('order_status')->onDelete('set null');
        });


        Schema::create('order_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('product')->onDelete('set null');
            $table->string('title');
            $table->integer('tax_rate_id')->unsigned()->nullable();
            $table->foreign('tax_rate_id')->references('id')->on('tax_rate')->onDelete('set null');
            $table->decimal('tax_rate', 12, 4)->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('weight')->nullable();
            $table->decimal('price_with_tax', 12, 4)->nullable();
            $table->decimal('price_without_tax', 12, 4)->nullable();
            $table->decimal('total_price_with_tax', 12, 4)->nullable();
            $table->decimal('total_price_without_tax', 12, 4)->nullable();
            $table->decimal('original_price_with_tax', 12, 4)->nullable();
            $table->decimal('original_price_without_tax', 12, 4)->nullable();
            $table->decimal('original_total_price_with_tax', 12, 4)->nullable();
            $table->decimal('original_total_price_without_tax', 12, 4)->nullable();
            
            $table->timestamps();
        });

        Schema::table('order_product', function (Blueprint $table) {
            $table->string('reference_code')->nullable();
            $table->string('product_attribute_title')->nullable();
            $table->integer('product_attribute_id')->unsigned()->nullable();
            $table->foreign('product_attribute_id')->references('id')->on('product_attribute')->onDelete('set null');
        });


        Schema::create('order_address', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
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
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
        });

        Schema::table('order', function (Blueprint $table) {
            $table->integer('delivery_order_address_id')->unsigned()->nullable();
            $table->integer('bill_order_address_id')->unsigned()->nullable();
            $table->foreign('delivery_order_address_id')->references('id')->on('order_address')->onDelete('set null');
            $table->foreign('bill_order_address_id')->references('id')->on('order_address')->onDelete('set null');
        });


        Schema::create('order_sending_method', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->integer('sending_method_id')->unsigned()->nullable();
            $table->foreign('sending_method_id')->references('id')->on('sending_method')->onDelete('set null');
            $table->string('title');
            $table->decimal('price_with_tax', 12, 4)->nullable();
            $table->decimal('price_without_tax', 12, 4)->nullable();
            $table->bigInteger('weight')->nullable();
            $table->decimal('tax_rate', 12, 4)->nullable();
            $table->integer('tax_rate_id')->unsigned()->nullable();
            $table->foreign('tax_rate_id')->references('id')->on('tax_rate')->onDelete('set null');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
        });


        Schema::create('order_payment_method', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->integer('payment_method_id')->unsigned()->nullable();
            $table->foreign('payment_method_id')->references('id')->on('payment_method')->onDelete('set null');
            $table->string('title');
            $table->decimal('price_with_tax', 12, 4)->nullable();
            $table->decimal('price_without_tax', 12, 4)->nullable();
            $table->boolean('percent_of_total')->default(false);
            $table->boolean('payment_external')->default(false);
            $table->decimal('tax_rate', 12, 4)->nullable();
            $table->integer('tax_rate_id')->unsigned()->nullable();
            $table->foreign('tax_rate_id')->references('id')->on('tax_rate')->onDelete('set null');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
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
        //
    }
}
