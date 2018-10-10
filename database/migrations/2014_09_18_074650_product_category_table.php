<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductCategoryTable extends Migration
{

    /**
     * Make changes to the table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->string('title')->nullable();
            $table->string('product_category_highlight_title')->nullable();
            $table->string('product_overview_title')->nullable();
            $table->text('product_overview_description')->nullable();            
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('slug');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->integer('redirect_product_category_id')->unsigned()->nullable();
            $table->foreign('redirect_product_category_id')->references('id')->on('product_category')->onDelete('set null');
            $table->integer('parent_id')->nullable()->index();
            $table->integer('lft')->nullable()->index();
            $table->integer('rgt')->nullable()->index();
            $table->integer('depth')->nullable();
            $table->timestamps();
        });

        Schema::create('product_category_image', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file')->nullable();
            $table->string('path')->nullable();
            $table->integer('size')->nullable();
            $table->string('extension')->nullable();
            $table->integer('rank')->default(0);
            $table->string('tag')->nullable();
            $table->integer('product_category_id')->unsigned();
            $table->foreign('product_category_id')->references('id')->on('product_category')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('product_category_related_extra_field', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('extra_field_id')->unsigned()->nullable();
            $table->foreign('extra_field_id', 'pcref_extra_field_id_fk')->references('id')->on('extra_field')->onDelete('cascade');
            $table->integer('product_category_id')->unsigned()->nullable();
            $table->foreign('product_category_id', 'pcref_product_category_id_fk')->references('id')->on('product_category')->onDelete('cascade');
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
