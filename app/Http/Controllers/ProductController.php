<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Hossam\Licht\Controllers\LichtBaseController;

class ProductController extends Controller
{
    public function index()
    {
        $products = ProductResource::collection(Product::where('is_active', 1)->get());
        return view('products', compact('products'));
    }

    public function store(StoreProductRequest $request)
    {

        $validData = $request->validated();
        $validData['cover'] = $this->uploadFile($validData['cover'], Product::PathToStoredImages);
        $product = Product::create($validData);
        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        return $this->successResponse(ProductResource::make($product));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validData = $request->validated();
        if ($request->hasFile('cover')) {
            $this->deleteFile($product->cover);
            $validData['cover'] = $this->uploadFile($request->file('cover'), Product::PathToStoredImages);
        }
        $product->update($validData);
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $this->deleteFile($product->cover);
        $product->delete();
        return redirect()->route('products.index');
    }
    public function apiIndex()
    {
        $products = ProductResource::collection(Product::all());
        return $this->apiResponse($products);
    }
}
