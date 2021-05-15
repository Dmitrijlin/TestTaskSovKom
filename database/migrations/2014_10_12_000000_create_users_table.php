<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('last_name');
            $table->string('name');
            $table->string('patronymic');
            $table->dateTime('birth_day');
            $table->boolean('blocked')->default(false);
            $table->rememberToken();
            $table->timestamp('created_at')->nullable()->default(now());
            $table->timestamp('updated_at')->nullable();
        });

        Artisan::call('db:seed');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
