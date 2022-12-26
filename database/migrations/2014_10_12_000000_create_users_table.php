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
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('job')->nullable();
            $table->string('phone_NO')->nullable();
            $table->string('phone_NO2')->nullable();
            $table->string('phone_NO3')->nullable();
            $table->text('image')->nullable();
            $table->string('company_NO')->nullable();
            $table->string('company_name')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('status')->default('offline');
            $table->boolean('private_status')->default(false);
            $table->rememberToken();
            $table->softDeletes();
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
}
