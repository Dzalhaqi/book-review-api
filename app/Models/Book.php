<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Book extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */

  protected $fillable = [
    'title',
    'author',
    'user_id',
    'publisher',
    'year_published',
    'isbn',
    'category',
    'description',
    'book_image',
    'review',
  ];


  /**
   * Relationship to User
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */

  protected $hidden = [
    'remember_token',
  ];

  /**
   * Set the book's image to default if not provided.
   *
   * @param string $value
   */
  public function setBookImageAttribute($value)
  {
    $this->attributes['book_image'] = $value ?? 'book-cover.jpeg';
  }
}
