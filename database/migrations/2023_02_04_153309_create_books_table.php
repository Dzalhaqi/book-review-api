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
    Schema::create('books', function (Blueprint $table) {
      $table->id()->autoIncrement();
      $table->foreignId('user_id')
        ->constrained('users')
        ->onDelete('cascade')
        ->onUpdate('cascade');
      $table->string('title');
      $table->string('author');
      $table->string('publisher');
      $table->string('year_published');
      $table->string('isbn')->min(13)->max(13);
      $table->string('category')->min(3);
      $table->text('descriptionp')->min(100);
      $table->longText('book_image')->nullable()->default('book-cover.jpeg');
      $table->text('review')->nullable();
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
    Schema::dropIfExists('books');
  }
};
