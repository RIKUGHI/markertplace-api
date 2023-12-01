<?php 

namespace App\Helper;

class Api {
  public static function sendResponse(int $status, $message, $data) {
    return response()->json([
      'status' => $status,
      'message' => $message,
      'data' => $data
    ], $status);
  }
}
