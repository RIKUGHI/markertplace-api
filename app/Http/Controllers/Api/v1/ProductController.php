<?php

namespace App\Http\Controllers\Api\v1;

use App\DTO\ProductDTO;
use App\Helper\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    public function index(Request $request)
    {
        $products = Product::orderByDesc('id')->get();

        return Api::sendResponse(200, null, ProductResource::collection($products));
    }

    public function create(ProductRequest $request)
    {
        try {
            return $this->productService->store(ProductDTO::fromApiRequest($request));
        } catch (\Throwable $th) {
            return Api::sendResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, "Internal Server Error", null);
        }
    }

    public function delete(string $id)
    {
        try {
            return $this->productService->destroy($id);
        } catch (\Throwable $th) {
            if ($th instanceof HttpResponseException) return $th->getResponse();

            return Api::sendResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, "Internal Server Error", null);
        }
    }
}
