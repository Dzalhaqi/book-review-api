<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{

  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Book::class;
  private $baseCreatedAt = '2021-01-01 00:00:00';

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'user_id' => fake()->numberBetween(1, 10),
      'title' => fake()->sentence(),
      'author' => fake()->name(),
      'publisher' => fake()->company(),
      'year_published' => fake()->year(),
      'isbn' => fake()->isbn13(),
      'category' => fake()->word(),
      'description' => fake()->paragraph(),
      'review' => fake()->paragraph(),
      'created_at' => fake()->dateTimeBetween($this->baseCreatedAt, '+1 day')->format('Y-m-d H:i:s'),
      'updated_at' => fake()->dateTimeBetween($this->baseCreatedAt, '+1 day')->format('Y-m-d H:i:s'),
    ];
  }
}
