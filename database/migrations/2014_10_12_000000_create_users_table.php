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
      $table->id()->autoIncrement();
      $table->string('name');
      $table->string('email')->unique();
      $table->string('phone')->unique();
      $table->string('address')->nullable();
      $table->string('role')->default('user');
      $table->string('image_profile')->default('user.png');
      $table->string('status')->default('active');
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->timestamp('created_at')->nullable();
      $table->timestamp('updated_at')->nullable();
      $table->rememberToken();
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
