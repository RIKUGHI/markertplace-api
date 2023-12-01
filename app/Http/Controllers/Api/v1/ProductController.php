<?php

namespace App\Http\Controllers\Api\v1;

use App\Helper\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderByDesc('id')->get();

        return Api::sendResponse(200, null, ProductResource::collection($products));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'description' => ['required'],
        ]);

        if ($validator->fails()) {
            return Api::sendResponse(401, $validator->errors(), null);
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'user_id' => Auth::user()->id
        ]);

        return Api::sendResponse(200, "", ProductResource::make($product));
    }

    public function delete(string $id)
    {
        $product = Product::query()
            ->where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$product) {
            return Api::sendResponse(404, "product not found", null);
        }

        $product->delete();

        return Api::sendResponse(200, null, null);
    }
}
