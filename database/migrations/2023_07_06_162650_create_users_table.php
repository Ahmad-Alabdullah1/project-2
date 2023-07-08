<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('firstName', 25);
            $table->string('lastName',25);
            $table->string('email', 60 )->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('mobile' )->nullable();
            $table->date('birthDate')->nullable();
            $table->string('profilePhoto')->nullable();
            $table->boolean('notificationDisable')->default(false);
            $table->bigInteger('address_id')->unsigned();
            $table->foreign('address_id')->references('address_id')->on('Regions');
            //$table->rememberToken();
            $table->boolean('isActive')->default(true);
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
        Schema::dropIfExists('users');
    }
};
