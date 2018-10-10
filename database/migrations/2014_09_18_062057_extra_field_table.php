<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraFieldTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_field', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('all_products')->default(false);
            $table->boolean('filterable')->default(false);
            $table->string('title')->nullable()->unique();
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('extra_field_default_value', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->integer('extra_field_id')->unsigned();
            $table->foreign('extra_field_id')->references('id')->on('extra_field')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
            $table->unique(array('extra_field_id','value'), 'unique_extra_field_default_value');
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
