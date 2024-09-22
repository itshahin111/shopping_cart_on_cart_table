<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function ProductList()
    {
        $products = Product::all();
        return view('pages.products', compact('products'));
    }

    public function CartListPage()
    {
        return view('pages.cart');
    }

    public function CreateCartList(Request $request): JsonResponse
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        $product = Product::findOrFail($product_id);
        $qty = $request->input('qty', 1);

        $unitPrice = $product->discount ? $product->discount_price : $product->price;
        $totalPrice = $qty * $unitPrice;

        // Using updateOrCreate to handle cart items
        $data = Cart::updateOrCreate(
            ['user_id' => $user_id, 'product_id' => $product_id],
            [
                'qty' => $qty,
                'price' => $totalPrice
            ]
        );

        return ResponseHelper::Out('Product added to cart successfully', $data, 200);
    }

    public function CartList(Request $request): JsonResponse
    {
        $user_id = $request->header('id');
        $data = Cart::where('user_id', $user_id)->with('product')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    public function UpdateCartQuantity(Request $request): JsonResponse
    {
        $user_id = $request->header('id');
        $product_id = $request->input('product_id');
        $qty = $request->input('qty');

        $cartItem = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();

        if ($cartItem) {
            $cartItem->qty = $qty;
            $cartItem->price = $qty * $cartItem->product->price; // Assuming the product's price is stored in the product model
            $cartItem->save();

            // Return the new cart data and the new price
            $cartItems = Cart::where('user_id', $user_id)->with('product')->get();
            return ResponseHelper::Out('success', ['data' => $cartItems, 'newPrice' => $cartItem->price], 200);
        }

        return ResponseHelper::Out('error', 'Cart item not found', 404);
    }

    public function DeleteCartList(Request $request, $id): JsonResponse
    {
        $user_id = $request->header('id');
        $deleted = Cart::where('user_id', $user_id)->where('product_id', $id)->delete();

        if ($deleted) {
            return ResponseHelper::Out('success', 'Item removed from cart', 200);
        }
        return ResponseHelper::Out('error', 'Item not found in cart', 404);
    }

}