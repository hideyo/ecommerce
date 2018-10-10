<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraFieldExtraFieldTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extra_field', function (Blueprint $table) {
            $table->integer('product_category_id')->unsigned()->nullable();
            $table->foreign('product_category_id')->references('id')->on('product_category')->onDelete('set null');
        });

        Schema::create('extra_field_related_product_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('extra_field_id')->unsigned();
            $table->foreign('extra_field_id', 'unique_related_extra_field_id')->references('id')->on('extra_field')->onDelete('cascade');
            $table->integer('product_category_id')->unsigned();
            $table->foreign('product_category_id', 'unique_related_category_id')->references('id')->on('product_category')->onDelete('cascade');
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
