<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->primary('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('fullName');
            $table->date('birthday');
            $table->foreign('dependency')->references('id')->on('dependencies');
            $table->foreign('role')->references('id')->on('roles');
            $table->bool('isActive');
            //$table->timestamps();
        });
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
