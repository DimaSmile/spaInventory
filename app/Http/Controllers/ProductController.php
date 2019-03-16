<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $products = Product::with('category')->get();
        $products = Product::all();
        return ProductResource::collection($products);
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
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name'        => 'required',
                'sku'         => 'unique:products|required',
                'dropPrice'   => 'nullable|integer',
                'retailPrice' => 'nullable|integer',
                'image'       => 'image|mimes:jpeg,png,jpg,gif,svg'//required
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error',
                'errors'  => $exception->errors(),
            ], 422);
        }
        // ['name', 'drop_price', 'retail_price', 'image_url', 'image_name', 'category_id'];
        // Product::create($request->all());
        $newProduct = new Product;
        if ($request->hasFile('image')) {
            $imageName = $request->image->getClientOriginalName();
            $imagePath = $request->image->store('uploads', 'public');//use storeAs for specify filename
            $newProduct->image_name = $imageName;
            $newProduct->image_url = $imagePath;
        }
        $newProduct->name = $request->name;
        $newProduct->sku = $request->sku;
        $newProduct->drop_price = $request->dropPrice;

        $newProduct->retail_price = $request->retailPrice;
        
        $newProduct->category_id = 1;
        $newProduct->save();

        return $newProduct;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // echo 11111;exit;
        // echo '<pre>';
        // echo 11232123;
        // var_dump($request->all());exit;
        // var_dump($product);exit;
        // var_dump($product);exit;
        try {
            $this->validate($request, [
                'name'        => 'required',
                // 'sku'         => 'unique:products|required',
                'dropPrice'   => 'nullable|integer',
                'retailPrice' => 'nullable|integer',
                'image'       => 'image|mimes:jpeg,png,jpg,gif,svg'//required
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error',
                'errors'  => $exception->errors(),
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = $request->image->getClientOriginalName();
            $imagePath = $request->image->store('uploads', 'public');//use storeAs for specify filename
            $product->image_name = $imageName;
            $product->image_url = $imagePath;
        }
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->drop_price = $request->dropPrice;

        $product->retail_price = $request->retailPrice;
        
        // $product->category_id = 1;
        $product->save();

        return $product;
        // $product->update($request->all());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json('successfully deleted');
    }
}
