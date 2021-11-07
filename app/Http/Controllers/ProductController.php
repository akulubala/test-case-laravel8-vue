<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use League\CommonMark\Inline\Element\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return Inertia::render('Product/Index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Product/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = $request->validate([
            'title' => ['required', 'max:90'],
            'price' => ['required', 'numeric'],
            'qty' => ['required', 'numeric'],
            'image' => ['required'],
            'description' => ['required'],
        ]);
        if ($valid) {
            $inputs = $request->only('title', 'price', 'qty', 'description');

            if ($request->hasFile('image')) {
                $inputs['image'] = $request->file('image')->store('image', 'public');
            }

            Product::create($inputs);
            return Redirect::route('product.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return Inertia::render('Product/Edit', [
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'qty' => $product->qty,
                'image' => $product->image,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => ['required', 'max:90'],
            'price' => ['required', 'numeric'],
            'qty' => ['required', 'numeric'],
            'description' => ['required'],
        ]);
        if ($data) {
            $inputs = $request->only('title', 'price', 'qty', 'description');

            if ($request->hasFile('image')) {
                $inputs['image'] = $request->file('image')->store('image', 'public');
            }
            
            $product->update($inputs);
        }
       
        return Redirect::route('product.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $product->delete();

        return Redirect::route('product.index');
    }
}
