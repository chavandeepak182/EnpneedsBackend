<?php
 
namespace App\Http\Controllers;
 
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

 
class ProductController extends Controller
{
    public function index()
    {
        $products = auth()->user()->products;
 
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
 
    public function show($id)
    {
        $product = auth()->user()->products()->find($id);
 
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $product->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|integer'
        ]);
 
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
 
        if (auth()->user()->products()->save($product))
            return response()->json([
                'success' => true,
                'data' => $product->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Product could not be added'
            ], 500);
    }
 
    public function update(Request $request,$id)
    {
       // Log::info('Update product: '.$id);
        //Log::info('Request: '.$request);
        //$product=new Product();
      // $product=new Product();
     // $product=Product::find($id);
       $product = auth()->user()->products()->find($id);
       if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'request with id ' . $id . ' not found'
        ], 400);
    }
     //  $product->name = $request->input('name');
     //  $product->price = $request->input('price');
       $product->update($request->all());  
       $product->save();
       //return response()->json($product);
        if ($product)
            return response()->json([
                'success' => true,
                'data'=>$product
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Product could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $product = auth()->user()->products()->find($id);
 
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($product->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product could not be deleted'
            ], 500);
        }
    }
}
