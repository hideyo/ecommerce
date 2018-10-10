<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Highlight extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('highlight', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('tag');
            $table->string('url');
            $table->string('image_file_name')->nullable();
            $table->string('image_file_path')->nullable();
            $table->integer('image_file_size')->nullable();
            $table->string('image_file_extension')->nullable();
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
