<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Book;
use App\Utils\ImageName;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;

class BookController extends BaseController
{
  /**
   * Create constructor
   *
   *  @return void
   */
  public function __construct()
  {
    $this->middleware('jwt.verify')->except(['index', 'show']);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $page = (int)$request->get("page", 1);

    $books = Book::select("id", "author", "title", "category", "book_image")->paginate(10);

    $lastPage = $books->lastPage();

    if ($page > $lastPage || $page < 1) {
      return $this->sendError("Resources with given parameter was not found", []);
    }

    return $this->sendResponse($books, 'Books retrieved successfully.');
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreBookRequest $request, Book $book)
  {
    $validatedData = $request->validatedWithCasts();

    $book->create($validatedData);

    return $this->sendResponse($validatedData, 'Book created successfully.', 201);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $book = Book::find($id);

    if (is_null($book)) {
      return $this->sendError('Book not found.');
    } else {
      return $this->sendResponse($book, 'Book retrieved successfully.');
    }
  }

  /**
   * Edit the specified resource in storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    // 
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateBookRequest $request, Book $book)
  {
    $validatedData = $request->validatedWithCasts();

    $book->update($validatedData);

    return $this->sendResponse($validatedData, 'Book updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    $book = Book::find($id);

    if ($request->user()->id !== $book->user_id) {
      return $this->sendError('You are not authorized to delete this book.');
    }

    if (is_null($book)) {
      return $this->sendError('Book not found.');
    } else {
      $book->delete();
      return $this->sendResponse([], 'Book deleted successfully.');
    }
  }
}
