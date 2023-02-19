<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use \Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Utils\ImageName;

class StoreBookRequest extends FormRequest
{
  /**
   * Handle a request before validation attempt.
   * 
   * @return void
   */
  protected function prepareForValidation()
  {
    $this->merge([
      'year_published' => $this->year_published
        ? date("Y-m-d", strtotime($this->year_published))
        : null,
    ]);
  }


  /**
   * Handle a failed validation attempt.
   *
   * @param  \Illuminate\Contracts\Validation\Validator  $validator
   * @return void
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(response()->json([
      'success' => false,
      'status_code' => 422,
      'message' => 'Validation Error.',
      'response' => $validator->errors(),
    ], 422));
  }


  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  public function validatedWithCasts()
  {
    $validatedData = $this->validator->validated();
    $validatedData['user_id'] = $this->user()->id;

    if ($this->hasFile('book_image')) {
      $imageFile = $this->file('book_image');
      $imageName = ImageName::generate($imageFile, $this->title);
      $validatedData['book_image'] = ImageName::generate($imageFile, $this->title);
      $imageFile->storeAs('public/book_images', $imageName);
    }

    return $validatedData;
  }


  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  public function rules()
  {
    return [
      'title'          => ['required', 'string', 'max:255'],
      'author'         => ['required', 'string', 'max:255'],
      'publisher'      => ['required', 'string'],
      'year_published' => ['required', 'date_format:Y-m-d', 'before:today'],
      'isbn'           => ['required', 'string', 'size:13'],
      'category'       => ['required', 'string', 'max:255'],
      'description'    => ['required', 'string',],
      'review'         => ['required', 'string',],
      'book_image'     => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ];
  }


  /**
   * Get the error messages for the defined validation rules.
   *
   * @return array<string, mixed>
   */
  public function messages()
  {
    return [
      'title.required'             => 'A title is required',
      'title.string'               => 'A title must be a string',
      'title.max'                  => 'A title must be less than 255 characters',
      'author.required'            => 'An author is required',
      'author.string'              => 'An author must be a string',
      'author.max'                 => 'An author must be less than 255 characters',
      'publisher.required'         => 'A publisher is required',
      'publisher.string'           => 'A publisher must be a string',
      'year_published.required'    => 'A year published is required',
      'year_published.date_format' => 'A year published must be in the format YYYY-MM-DD',
      'year_published.before'      => 'A year published must be before today',
      'isbn.required'              => 'An ISBN is required',
      'isbn.string'                => 'An ISBN must be a string',
      'isbn.size'                  => 'An ISBN must be 13 characters',
      'category.required'          => 'A category is required',
      'category.string'            => 'A category must be a string',
      'category.max'               => 'A category must be less than 255 characters',
      'description.required'       => 'A description is required',
      'description.string'         => 'A description must be a string',
      'review.required'            => 'A review is required',
      'review.string'              => 'A review must be a string',
      'book_image.image'           => 'A book image must be an image',
      'book_image.mimes'           => 'A book image must be a file of type: jpeg, png, jpg, gif, svg',
      'book_image.max'             => 'A book image must be less than 2048 kilobytes',
    ];
  }
}
