<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductCategoryHighlightProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category_highlight_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id', 'pchp_product_id_fk')->references('id')->on('product')->onDelete('cascade');
            $table->integer('product_category_id')->unsigned();
            $table->foreign('product_category_id', 'pchp_product_category_id_fk')->references('id')->on('product_category')->onDelete('cascade');
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
