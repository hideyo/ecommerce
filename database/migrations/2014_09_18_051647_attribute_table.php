<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AttributeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->enum('type', array('selectbox'))->default('selectbox');
            $table->enum('filter_type', array('selectbox', 'checkbox'))->nullable();
            $table->boolean('filter')->default(false);            
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
            $table->unique(array('title','shop_id'), 'unique_attribute_group_title');
        });


        Schema::create('attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->integer('attribute_group_id')->unsigned();
            $table->foreign('attribute_group_id')->references('id')->on('attribute_group')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
            $table->unique(array('attribute_group_id','value'), 'unique_attribute_value');
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
