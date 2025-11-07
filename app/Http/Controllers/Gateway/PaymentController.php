<?php

namespace App\Http\Controllers\Gateway;

use App\Models\Sell;
use App\Models\User;
use App\Models\Order;
use App\Models\Deposit;
use App\Constants\Status;
use App\Lib\FormProcessor;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\GatewayCurrency;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function deposit()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();
        $pageTitle = 'Deposit Methods';
        return view('Template::user.payment.deposit', compact('gatewayCurrency', 'pageTitle'));
    }

    public function depositInsert(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'gateway' => 'required',
            'currency' => 'required',
            'name' => 'sometimes|required',
            'mobile' => 'sometimes|required',
            'email' => 'sometimes|required|email',
            'address' => 'sometimes|required'
        ]);

        $user = auth()->user();

        $orders = Order::where('order_number', $user->id)->get();


        if (count($orders) > 0) {

            $gate = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', Status::ENABLE);
            })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();
            if (!$gate) {
                $notify[] = ['error', 'Invalid gateway'];
                return back()->withNotify($notify);
            }

            if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
                $notify[] = ['error', 'Please follow deposit limit'];
                return back()->withNotify($notify);
            }

            $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
            $payable = $request->amount + $charge;
            $finalAmount = $payable * $gate->rate;

            $data = new Deposit();
            $data->user_id = $user->id;
            $data->method_code = $gate->method_code;
            $data->method_currency = strtoupper($gate->currency);
            $data->amount = $request->amount;
            $data->charge = $charge;
            $data->rate = $gate->rate;
            $data->final_amount = $finalAmount;
            $data->btc_amount = 0;
            $data->btc_wallet = "";
            $data->trx = getTrx();
            $data->success_url = urlPath('user.purchase.log');
            $data->failed_url = urlPath('user.purchase.log');
            $data->shipping =  [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->mobile,
                'address' => $request->address
            ];

            $data->save();
            session()->put('Track', $data->trx);
            return to_route('user.deposit.confirm');
        } else {
            $notify[] = ['error', 'You have no items in cart'];
            return redirect()->route('home')->withNotify($notify);
        }
    }

    public function depositConfirm()
    {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }


        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return back()->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        return view("Template::$data->view", compact('data', 'pageTitle', 'deposit'));
    }


    public static function userDataUpdate($deposit)
    {
        $orders = Order::where('order_number', auth()->user()->id)->get();

        if (count($orders) <= 0) {
            return false;
        }

        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {
            $user = User::find($deposit->user_id);
            $code = getTrx(8);

            foreach ($orders as $item) {
                $sell                 = new Sell();
                $sell->code           = $code;
                $sell->user_id        = $user->id;
                $sell->product_id     = $item->product_id;
                $sell->qty            = $item->qty;
                $sell->product_price  = $item->product_price;
                $sell->total_price    = $item->total_price;
                $sell->status         = Status::PROCESSING;
                $sell->payment_status = Status::SELL_PAYMENT_PAID;
                $sell->save();

                $sell->product->total_sell += $item->qty;
                $sell->product->save();
            }

            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->code = $code;
            $deposit->save();

            $user = User::find($deposit->user_id);
            $methodName = $deposit->methodName();

            $transaction           = new Transaction();
            $transaction->user_id  = $deposit->user_id;
            $transaction->amount   = $deposit->amount;
            $transaction->charge   = $deposit->charge;
            $transaction->trx_type = '+';
            $transaction->details  = 'Payment Via ' . $methodName;
            $transaction->trx      = $deposit->trx;
            $transaction->remark   = 'payment';
            $transaction->save();

            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $user->id;
            $adminNotification->title     = 'Payment successful via ' . $methodName;
            $adminNotification->click_url = urlPath('admin.deposit.successful');
            $adminNotification->save();

            notify($user, 'PAYMENT_COMPLETE', [
                'method_name'     => $methodName,
                'method_currency' => $deposit->method_currency,
                'method_amount'   => showAmount($deposit->final_amount, currencyFormat: false),
                'amount'          => showAmount($deposit->amount, currencyFormat: false),
                'charge'          => showAmount($deposit->charge, currencyFormat: false),
                'rate'            => showAmount($deposit->rate, currencyFormat: false),
                'trx'             => $deposit->trx,
            ]);

            foreach ($orders as $item) {
                $item->delete();
            }
        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        if ($data->method_code > 999) {
            $pageTitle = 'Confirm Deposit';
            $method = $data->gatewayCurrency();
            $gateway = $method->method;
            return view('Template::user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {

        $orders = Order::where('order_number', auth()->user()->id)->get();

        if (count($orders) < 1) {
            $notify[] = ['error', 'You have no items in cart'];
            return redirect()->route('home')->withNotify($notify);
        }

        $code = getTrx(8);
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway = $gatewayCurrency->method;
        $formData = $gateway->form->form_data;

        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $data->detail = $userData;
        $data->code = $code;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();

        $user = User::find($data->user_id);
        foreach ($orders as $item) {
            $sell                 = new Sell();
            $sell->code           = $code;
            $sell->user_id        = $user->id;
            $sell->product_id     = $item->product_id;
            $sell->qty            = $item->qty;
            $sell->product_price  = $item->product_price;
            $sell->total_price    = $item->total_price;
            $sell->status         = Status::PENDING;
            $sell->payment_status = Status::SELL_PAYMENT_PENDING;
            $sell->save();
        }

        foreach ($orders as $item) {
            $item->delete();
        }

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $data->user->id;
        $adminNotification->title = 'Payment request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'PAYMENT_REQUEST', [
            'method_name' => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => showAmount($data->final_amount, currencyFormat: false),
            'amount' => showAmount($data->amount, currencyFormat: false),
            'charge' => showAmount($data->charge, currencyFormat: false),
            'rate' => showAmount($data->rate, currencyFormat: false),
            'trx' => $data->trx
        ]);

        $notify[] = ['success', 'You have deposit request has been taken'];
        return to_route('user.purchase.log')->withNotify($notify);
    }
}
