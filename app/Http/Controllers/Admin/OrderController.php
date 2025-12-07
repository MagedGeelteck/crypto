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
        $deposit = Deposit::where('code', $code)->first();
        return view('admin.order.order_details', compact('pageTitle', 'orderDetails', 'deposit'));
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

        // Send order completion email to customer
        $deposit = Deposit::where('code', $request->code)->first();
        if ($deposit && isset($deposit->shipping['email']) && !empty($deposit->shipping['email'])) {
            try {
                $sellsWithProduct = Sell::where('code', $request->code)->with('product')->get();
                $totalAmount = $sellsWithProduct->sum('total_price');
                
                \Illuminate\Support\Facades\Log::info('Sending completion email to: ' . $deposit->shipping['email']);
                
                \Illuminate\Support\Facades\Mail::to($deposit->shipping['email'])->send(
                    new \App\Mail\OrderStatusUpdate($deposit, $sellsWithProduct, $totalAmount, 'Completed', '#10b981')
                );
                
                \Illuminate\Support\Facades\Log::info('Completion email sent successfully');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send completion email: ' . $e->getMessage());
            }
        } else {
            \Illuminate\Support\Facades\Log::warning('No PayPal email found for completed order: ' . $request->code);
        }

        $notify[] = ['success', 'Order has been marked as completed successfully'];
        return back()->withNotify($notify);
    }
}
