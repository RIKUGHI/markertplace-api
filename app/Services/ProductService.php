<?php 

namespace App\Services;

use App\DTO\LoginDTO;
use App\DTO\ProductDTO;
use App\DTO\RegistrationDTO;
use App\Helper\Api;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProductService {
  public function store(ProductDTO $dto) {
    $product = Product::create([
      'name' => $dto->name,
      'price' => $dto->price,
      'description' => $dto->description,
      'user_id' => Auth::user()->id
    ]);

    return Api::sendResponse(JsonResponse::HTTP_OK, null, ProductResource::make($product));
  }

  public function destroy(string $id) {
    $product = Product::query()
      ->where('id', $id)
      ->where('user_id', Auth::user()->id)
      ->first();

    if (!$product) {
        throw new HttpResponseException(
          Api::sendResponse(JsonResponse::HTTP_NOT_FOUND, "Product not found", null)
        );
    }

    $product->delete();

    return Api::sendResponse(JsonResponse::HTTP_OK, null, null);
  }
}