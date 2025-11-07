<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sell;
use App\Models\Deposit;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function all($id = null)
    {
        $pageTitle = 'All Orders';
        $orders = $this->sellData(false,$id);
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }
    public function processing()
    {
        $pageTitle = 'Processing Orders';
        $orders = $this->sellData('processing');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function completed()
    {
        $pageTitle = 'Completed Orders';
        $orders = $this->sellData('delivered');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    public function rejected()
    {
        $pageTitle = 'Rejected Orders';
        $orders = $this->sellData('rejected');
        return view('admin.order.index', compact('pageTitle', 'orders'));
    }

    protected function sellData($scope = null,$id=null)
    {
        if ($scope) {
            $orders = Sell::$scope();
        } else {
            $orders = Sell::query();
        }
        if($id){
            $orders = $orders->where('user_id',$id);
        }
        return $orders->searchable(['code', 'product:name'])->dateFilter()->with('user')->latest()->groupBy('code')->paginate(getPaginate());
    }

    public function orderDetails($code)
    {
        $pageTitle = 'Order Details';
        $orderDetails = Sell::where('code', $code)->with(['user', 'product'])->get();
        $shippingAddress = Deposit::where('code', $code)->pluck('shipping');
        return view('admin.order.order_details', compact('pageTitle', 'orderDetails', 'shippingAddress'));
    }

    public function complete(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $sells = Sell::where('code', $request->code)->with('user')->get();

        foreach ($sells as $item) {
            $item->status = Status::DELIVERED;
            $item->save();
        }

        notify($sells[0]->user, 'PRODUCT_DELIVERED', [
            'code' => $sells[0]->code
        ]);

        $notify[] = ['success', 'Order has been marked as completed successfully'];
        return back()->withNotify($notify);
    }
}
