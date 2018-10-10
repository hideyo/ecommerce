<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(false);            
            $table->string('title')->nullable();
            $table->text('short_description')->nullable();
            $table->text('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('slug');
            $table->integer('shop_id')->unsigned()->nullable();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();
            $table->unique(array('title','shop_id'), 'unique_content_title');
        });

        Schema::create('content_image', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->unsigned();
            $table->foreign('content_id')->references('id')->on('content')->onDelete('cascade');
            ;
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
        Schema::create('content_group', function ($table) {
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->integer('rank')->default(0);
            $table->string('title');
            $table->string('tag')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');

            $table->string('slug');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->unique(array('title','shop_id'), 'unique_content_group_title');
            $table->timestamps();
        });


        Schema::table('content', function (Blueprint $table) {
            $table->integer('content_group_id')->unsigned()->nullable();
            $table->foreign('content_group_id', 'c_content_group_id_fk')->references('id')->on('content_group')->onDelete('set null');
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
