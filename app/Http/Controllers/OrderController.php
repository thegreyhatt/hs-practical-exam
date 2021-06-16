<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function order(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'product_id' => 'required',
    		'quantity' => 'required',
    	]);

    	if ($validator->fails()) {
    		return response()->json($validator->messages(), 200);
    	}

    	//get the product
    	$product = Product::findOrFail($request->product_id);

    	//check product availability
    	if ($product->available_stock < $request->quantity) {
    		return response([
    			'message' => 'Failed to order this product due to unavailability of the stock'
    		], 400);
    	}

    	//update product available stock
    	$product->update([
    		'available_stock' => $product->available_stock - $request->quantity
    	]);

    	//record the order
    	Order::create([
    		'product_id' => $request->product_id,
    		'quantity' => $request->quantity,
    	]);

    	return response([
    		'message' => 'You have successfully ordered this product.'
		], 201);
    }
}
