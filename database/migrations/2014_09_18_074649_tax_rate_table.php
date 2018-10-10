<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TaxRateTable extends Migration
{

    /**
     * Make changes to the table.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_rate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->decimal('rate', 12, 4)->nullable();
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shop')->onDelete('cascade');
            $table->integer('modified_by_user_id')->unsigned()->nullable();
            $table->foreign('modified_by_user_id')->references('id')->on('user')->onDelete('set null');
            $table->timestamps();

            $table->unique(array('rate','shop_id'), 'unique_tax_rate_rate');
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
