<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CouponTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->decimal('value', 12, 4)->nullable();
            $table->string('title')->nullable();
            $table->string('code')->nullable();
            $table->enum('type', array('total_price', 'product', 'product_category', 'sending_method', 'payment_method'));
            $table->enum('discount_way', array('percent', 'total'));
            $table->boolean('permanent')->default(false);
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->date('published_at')->nullable();
            $table->date('unpublished_at')->nullable();
            $table->timestamps();
            $table->unique(array('title','shop_id'), 'unique_coupon_title');
            $table->unique(array('code','shop_id'), 'unique_coupon_code');
        });

        // Creates the users table
        Schema::create('coupon_group', function ($table) {
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->string('title');

            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');

            $table->string('slug');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->unique(array('title','shop_id'), 'unique_coupon_group_title');
            $table->timestamps();
        });

        Schema::table('coupon', function (Blueprint $table) {
            $table->integer('coupon_group_id')->unsigned()->nullable();
            $table->foreign('coupon_group_id')->references('id')->on('coupon_group')->onDelete('set null');
        });

        Schema::create('coupon_product', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coupon_id');
            $table->foreign('coupon_id')->references('id')->on('coupon')->onDelete('cascade');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->unique(array('coupon_id','product_id'), 'unique_product_id');
            $table->timestamps();
        });

        Schema::create('coupon_product_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coupon_id');
            $table->foreign('coupon_id')->references('id')->on('coupon')->onDelete('cascade');
            $table->unsignedInteger('product_category_id');
            $table->foreign('product_category_id')->references('id')->on('product_category')->onDelete('cascade');
            $table->unique(array('coupon_id','product_category_id'), 'unique_product_category_id');
            $table->timestamps();
        });

        Schema::create('coupon_sending_method', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coupon_id');
            $table->foreign('coupon_id')->references('id')->on('coupon')->onDelete('cascade');
            $table->unsignedInteger('sending_method_id');
            $table->foreign('sending_method_id')->references('id')->on('sending_method')->onDelete('cascade');
            $table->unique(array('coupon_id','sending_method_id'), 'unique_sending_method_id');
            $table->timestamps();
        });

        Schema::create('coupon_payment_method', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coupon_id');
            $table->foreign('coupon_id')->references('id')->on('coupon')->onDelete('cascade');
            $table->unsignedInteger('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('payment_method')->onDelete('cascade');
            $table->unique(array('coupon_id','payment_method_id'), 'unique_payment_method_id');
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
