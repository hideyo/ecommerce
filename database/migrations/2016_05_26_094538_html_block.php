<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HtmlBlock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('html_block', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->string('title')->nullable();
            $table->string('button_title')->nullable();
            $table->string('short_title')->nullable();
            $table->text('content')->nullable();
            $table->text('template')->nullable();
            $table->string('slug');
            $table->string('position')->nullable();
            $table->string('url')->nullable();
            $table->string('image_file_name')->nullable();
            $table->string('image_file_path')->nullable();
            $table->integer('image_file_size')->nullable();
            $table->string('image_file_extension')->nullable();
            $table->string('thumbnail_height')->nullable();
            $table->string('thumbnail_width')->nullable();            
            $table->integer('shop_id')->unsigned()->nullable();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
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
