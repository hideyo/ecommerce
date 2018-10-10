<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientTable extends Migration
{

    /**
     * Make changes to the table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->enum('type', array('consumer', 'wholesale'))->default('consumer');            
            $table->boolean('newsletter')->default(false);            
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('new_email')->nullable();
            $table->string('new_password')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->string('remember_token')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->text('comments')->nullable();
            $table->string('company')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('iban_number')->nullable();
            $table->string('debtor_number')->nullable();
            $table->string('chamber_of_commerce_number')->nullable();
            $table->datetime('account_created')->nullable();
            $table->datetime('last_login')->nullable();          
            $table->longText('browser_detect')->nullable();
            $table->integer('language_id')->unsigned()->nullable();
            $table->foreign('language_id')->references('id')->on('language')->onDelete('set null');
            $table->integer('delivery_client_address_id')->unsigned()->nullable();
            $table->integer('bill_client_address_id')->unsigned()->nullable();            
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
            $table->unique(array('email','shop_id'), 'unique_email');
        });

        Schema::create('client_address', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')->on('client')->onDelete('cascade');
            $table->string('company')->nullable();
            $table->enum('gender', array('male', 'female'))->nullable();
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

        Schema::table('client', function (Blueprint $table) {
            $table->foreign('delivery_client_address_id')->references('id')->on('client_address')->onDelete('set null');
            $table->foreign('bill_client_address_id')->references('id')->on('client_address')->onDelete('set null');
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
