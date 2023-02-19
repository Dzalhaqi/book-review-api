<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = User::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition()
  {
    return [
      'name' => fake()->name(),
      'email' => fake()->unique(true)->email(),
      'phone' => fake()->unique(true)->phoneNumber(),
      'address' => fake()->address(),
      'password' => Hash::make('secretpassword'),
      'created_at' => now(),
      'updated_at' => now(),
      'remember_token' => Str::random(10),
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   *
   * @return static
   */
  // public function unverified()
  // {
  //   return $this->state(fn (array $attributes) => [
  //     'email_verified_at' => null,
  //   ]);
  // }
}
