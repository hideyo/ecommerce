<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Redirect404Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirect', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(false);
            $table->integer('clicks')->default(0);
            $table->string('url')->unique();
            $table->string('redirect_url')->nullable();
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
