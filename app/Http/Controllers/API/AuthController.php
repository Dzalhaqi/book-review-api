<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class AuthController extends BaseController
{
  /**
   * Create constructor
   *
   *  @return void
   */
  public function __construct()
  {
    $this->middleware('jwt.verify')->except(['login', 'register']);
  }

  /**
   * Get a JWT via given credentials
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => ['required', 'email:rfc,dns'],
      'password' => ['required'],
    ]);

    $token = auth()->attempt($validator->validated());

    if ($validator->fails()) {
      return $this->sendError('Validation Error.', $validator->errors());
    }

    if (!$token) {
      return $this->sendError('Unauthorized.', [], 403);
    }

    return $this->createNewToken($token);
  }

  /**
   * Register a User
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function register(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'name' => ['required', 'alpha_spaces', 'max:255'],
        'phone' => ['required', 'string', 'max:15', 'unique:users'],
        'email' => ['required', 'email:rfc,dns', 'unique:users'],
        'password' => ['required', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', 'required_with:password_confimation'],
        'password_confirmation' => ['same:password']
      ],
      [
        'name.required' => 'Name is required.',
        'name.alpha_spaces' => 'Name must be a string.',
        'name.max' => 'Name must not be greater than 255 characters.',
        'phone.required' => 'Phone is required.',
        'phone.max' => 'Phone must not be greater than 15 characters.',
        'phone.unique' => 'Phone has already been taken.',
        'email.required' => 'Email is required.',
        'email.email' => 'Email must be a valid email address.',
        'email.unique' => 'Email has already been taken.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password does not match',
        'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        'password.required_with' => 'Password confirmation is required.',
        'password_confirmation.same' => 'Password confirmation does not match with password.',
        'password_confirmation.min' => 'Password confirmation must be at least 8 characters.'
      ]
    );

    if ($validator->fails()) {
      return $this->sendError('Validation Error.', $validator->messages(), 401);
    }

    $user = User::create(array_merge(
      $validator->validated(),
      [
        'password' => Hash::make($request->get('password')),
        'remember_token' => Str::random(10),
      ]
    ));

    return $this->sendResponse($user, 'User successfully registered.', 201);
  }

  /**
   * Log the user out (Invalidate the token)
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth()->logout();
    return $this->sendResponse([], 'User successfully signed out.');
  }

  /**
   * Refresh a token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
  {
    return $this->createNewToken(auth()->refresh());
  }

  /**
   * Get the authenticated User
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function userProfile()
  {
    if (auth()->check()) {
      return $this->sendResponse([
        'user' => auth()->user(),
      ], 'User profile.');
    } else {
      return $this->sendError('Unauthorized.', [], 403);
    }
  }

  /**
   * Get the token array structure
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function createNewToken($token)
  {
    return $this->sendResponse([
      'access_token' => $token,
      'token_type' => 'bearer',
    ], 'User successfully logged in.');
  }

  /**
   * Get my token 
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function getMyToken(Request $request)
  {
    return $this->sendResponse([
      'access_token' => $request->bearerToken(),
    ], 'Successfully get token.');
  }
}
