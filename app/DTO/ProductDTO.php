<?php 

namespace App\DTO;

use App\Http\Requests\ProductRequest;

class ProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $price,
        public readonly string $description,
    ) {

    }

    public static function fromApiRequest(ProductRequest $request)
    {
        return new self(
            name: $request->validated('name'),
            price: $request->validated('price'),
            description: $request->validated('description'),
        );
    }
}