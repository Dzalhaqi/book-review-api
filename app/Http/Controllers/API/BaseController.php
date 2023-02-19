<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
  /**
   * success response method.
   *
   * @return \Illuminate\Http\Response
   */
  public function sendResponse($result, $message, $code = 200)
  {
    $response = [
      'success'     => true,
      'status_code' => $code,
      'message'     => $message,
      'response'    => $result,
    ];

    if (empty($result)) {
      unset($response['response']);
    }

    return response()->json($response, $code);
  }

  /**
   * return error response.
   *
   * @return \Illuminate\Http\Response
   */
  public function sendError($error, $errorMessages = [], $code = 404)
  {
    $response = [
      'success'     => false,
      'status_code' => $code,
      'message'     => $error,
    ];

    if (!empty($errorMessages)) {
      $response['response'] = $errorMessages;
    }

    return response()->json($response, $code);
  }
}
