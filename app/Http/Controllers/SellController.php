<?php

namespace App\Http\Controllers;

use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class SellController extends Controller
{
    public function addToCart(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'product_id' => 'integer|required|gt:0',
            'qty' => 'required|integer|gt:0',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()]);
        }
        $product = Product::active()->find($request->product_id);
        if ($product == null) {
            return response()->json(['error' => 'Product not found']);
        }

        if (auth()->user()) {
            $orderNumber = auth()->user()->id;
        } else {
            if (session()->has('order_number')) {
                $orderNumber = session()->get('order_number');
            }
            if (!session()->has('order_number')) {
                $orderNumber = getTrx(8);
                session()->put('order_number', $orderNumber);
            }
        }

        $checkOrder = Order::where('order_number', $orderNumber)->where('product_id', $product->id)->first();


        if ($checkOrder) {
            $checkOrder->qty = $checkOrder->qty + $request->qty;
            $checkOrder->product_price = $product->new_price;
            $checkOrder->total_price = $product->new_price * $checkOrder->qty;
            $checkOrder->save();
        } else {
            $order = new Order();
            $order->order_number = $orderNumber;
            $order->product_id = $product->id;
            $order->qty = $request->qty;
            $order->product_price = $product->new_price;
            $order->total_price = $product->new_price * $request->qty;
            $order->save();
        }

        $orders = Order::where('order_number', $orderNumber)->get();
        $view = view('Template::cart_product', compact('orders'))->render();
        return response()->json([
            'totalPrice' => $orders->sum('total_price'),
            'cartCount' => count($orders),
            'html' => $view,
            'success' => 'Product has been added to cart successfully'
        ]);
    }

    public function removeCart(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $order = Order::find(Crypt::decrypt($request->id));

        if ($order  == null) {
            return response()->json(['error' => 'Product not found']);
        }

        $order->delete();

        $currentOrder = Order::where('order_number', $order->order_number)->get();

        $shipable = 0;

        foreach ($currentOrder as  $item) {
            if ($item->product->p_file == null) {
                $shipable = 1;
                break;
            }
        }

        return response()->json([
            'cartCount' => count($currentOrder),
            'totalPrice' => getAmount($currentOrder->sum('total_price'), 8),
            'shipable' => $shipable,
        ]);
    }

    public function cart()
    {
        if (auth()->user()) {
            $orders = Order::where('order_number', auth()->user()->id)->with('product')->get();
        } elseif (session()->has('order_number')) {
            $orders = Order::where('order_number', session()->get('order_number'))->with('product')->get();
        } else {
            $notify[] = ['error', 'You have no cart.'];
            return redirect()->route('home')->withNotify($notify);
        }

        if (count($orders) > 0) {
            $pageTitle = 'Cart';
            $totalPrice = $orders->sum('total_price');
            $gatewayCurrency = GatewayCurrency::where('min_amount', '<', $totalPrice)->where('max_amount', '>', $totalPrice)->whereHas('method', function ($gate) {
                $gate->where('status', 1);
            })->with('method')->orderby('method_code')->get();

            $shipable = 0;

            foreach ($orders as  $item) {
                if ($item->product->p_file == null) {
                    $shipable = 1;
                    break;
                }
            }

            // Check how many transactions the user has made today
            // Only count completed/pending transactions from database, not initiated ones
            $transactionsToday = 0;
            if (auth()->user()) {
                $today = \Carbon\Carbon::today();
                $transactionsToday = \App\Models\Deposit::where('user_id', auth()->user()->id)
                    ->whereDate('created_at', $today)
                    ->whereIn('status', [\App\Constants\Status::PAYMENT_PENDING, \App\Constants\Status::PAYMENT_SUCCESS])
                    ->count();
            }

            return view('Template::user.checkout', compact('orders', 'pageTitle', 'totalPrice', 'gatewayCurrency', 'shipable', 'transactionsToday'));
        } else {
            $notify[] = ['error', 'You have no cart.'];
            return redirect()->route('home')->withNotify($notify);
        }
    }
}
