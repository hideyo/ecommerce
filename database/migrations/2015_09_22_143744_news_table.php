<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');              
            $table->string('title')->nullable();
            $table->text('short_description')->nullable();
            $table->text('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('slug');
            $table->date('published_at')->nullable(); 
            $table->integer('shop_id')->unsigned()->nullable();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
            $table->unique(array('title','shop_id'), 'unique_news_title');
        });


        Schema::create('news_image', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('news_id')->unsigned();
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->string('file')->nullable();
            $table->string('path')->nullable();
            $table->integer('size')->nullable();
            $table->string('extension')->nullable();
            $table->integer('rank')->default(0);
            $table->string('tag')->nullable();
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
        });

        // Creates the users table
        Schema::create('news_group', function ($table) {
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->integer('rank')->default(0);
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('slug');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->unique(array('title','shop_id'), 'unique_news_group_title');
            $table->timestamps();
        });


        Schema::table('news', function (Blueprint $table) {
            $table->integer('news_group_id')->unsigned()->nullable();
            $table->foreign('news_group_id')->references('id')->on('news_group')->onDelete('set null');
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
