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

        // if ($request->hasFile('image')) {
        //     return 'true';
        // }else{
        //     return 'false';
        // }
        try {
            $this->validate($request, [
                'name'        => 'required',
                'dropPrice'   => 'integer',
                'retailPrice' => 'integer',
                'image'       => 'file'
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
        $imageName = $request->image->getClientOriginalName();
        $imagePath = $request->image->store('uploads', 'public');//use storeAs for specify filename

        $newProduct = new Product;
        $newProduct->name = $request->name;
        $newProduct->drop_price = $request->dropPrice;
        $newProduct->retail_price = $request->retailPrice;
        $newProduct->image_url = $imagePath;
        $newProduct->image_name = $imageName;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
