<?php

use Illuminate\Database\Migrations\Migration;

class ConfideSetupUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('groups');
        Schema::dropIfExists('users_groups');
        Schema::dropIfExists('users');

        // Creates the users table
        Schema::create('user', function ($table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('confirmation_code');
            $table->string('remember_token')->nullable();
            $table->string('api_token')->unique()->nullable();
            $table->boolean('confirmed')->default(false);
            $table->integer('language_id')->unsigned()->nullable();
            $table->integer('selected_shop_id')->unsigned()->nullable();
            $table->foreign('selected_shop_id')->references('id')->on('shop')->onDelete('set null');           
            $table->timestamps();
        });

        // Creates password reminders table
        Schema::create('password_reminders', function ($table) {
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('password_reminders');
        Schema::drop('users');
    }
}
