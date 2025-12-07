<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sell;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Constants\Status;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepositController extends Controller
{
    public function pending($userId = null)
    {
        $pageTitle = 'Pending Deposits';
        $deposits = $this->depositData('pending', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }


    public function approved($userId = null)
    {
        $pageTitle = 'Approved Deposits';
        $deposits = $this->depositData('approved', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function successful($userId = null)
    {
        $pageTitle = 'Successful Deposits';
        $deposits = $this->depositData('successful', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function rejected($userId = null)
    {
        $pageTitle = 'Rejected Deposits';
        $deposits = $this->depositData('rejected', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function initiated($userId = null)
    {
        $pageTitle = 'Initiated Deposits';
        $deposits = $this->depositData('initiated', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function deposit($userId = null)
    {
        $pageTitle = 'Deposit History';
        $depositData = $this->depositData($scope = null, $summary = true, userId: $userId);
        $deposits = $depositData['data'];
        $summary = $depositData['summary'];
        $successful = $summary['successful'];
        $pending = $summary['pending'];
        $rejected = $summary['rejected'];
        $initiated = $summary['initiated'];
        return view('admin.deposit.log', compact('pageTitle', 'deposits', 'successful', 'pending', 'rejected', 'initiated'));
    }

    protected function depositData($scope = null, $summary = false, $userId = null)
    {
        if ($scope) {
            $deposits = Deposit::$scope()->with(['user', 'gateway']);
        } else {
            $deposits = Deposit::with(['user', 'gateway']);
        }

        if ($userId) {
            $deposits = $deposits->where('user_id', $userId);
        }

        $deposits = $deposits->searchable(['trx', 'user:username'])->dateFilter();

        $request = request();

        if ($request->method) {
            if ($request->method != Status::GOOGLE_PAY) {
                $method = Gateway::where('alias', $request->method)->firstOrFail();
                $deposits = $deposits->where('method_code', $method->code);
            } else {
                $deposits = $deposits->where('method_code', Status::GOOGLE_PAY);
            }
        }

        if (!$summary) {
            return $deposits->orderBy('id', 'desc')->paginate(getPaginate());
        } else {
            $successful = clone $deposits;
            $pending = clone $deposits;
            $rejected = clone $deposits;
            $initiated = clone $deposits;

            $successfulSummary = $successful->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
            $pendingSummary = $pending->where('status', Status::PAYMENT_PENDING)->sum('amount');
            $rejectedSummary = $rejected->where('status', Status::PAYMENT_REJECT)->sum('amount');
            $initiatedSummary = $initiated->where('status', Status::PAYMENT_INITIATE)->sum('amount');

            return [
                'data' => $deposits->orderBy('id', 'desc')->paginate(getPaginate()),
                'summary' => [
                    'successful' => $successfulSummary,
                    'pending' => $pendingSummary,
                    'rejected' => $rejectedSummary,
                    'initiated' => $initiatedSummary,
                ]
            ];
        }
    }

    public function details($id)
    {
        $deposit = Deposit::where('id', $id)->with(['user', 'gateway'])->firstOrFail();
        $pageTitle = $deposit->user->username . ' requested ' . showAmount($deposit->amount);
        $details = ($deposit->detail != null) ? json_encode($deposit->detail) : null;
        return view('admin.deposit.detail', compact('pageTitle', 'deposit', 'details'));
    }


    public function approve($id)
    {
        $deposit = Deposit::where('id', $id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();
        $deposit->status = Status::PAYMENT_SUCCESS;
        $deposit->save();

        $user                  = User::find($deposit->user_id);
        $transaction           = new Transaction();
        $transaction->user_id  = $deposit->user_id;
        $transaction->amount   = $deposit->amount;
        $transaction->charge   = $deposit->charge;
        $transaction->trx_type = '+';
        $transaction->details  = 'Payment Via ' . $deposit->gatewayCurrency()->name;
        $transaction->trx      = $deposit->trx;
        $transaction->save();

        notify($user, 'PAYMENT_APPROVE', [
            'method_name'     => $deposit->methodName(),
            'method_currency' => $deposit->method_currency,
            'method_amount'   => showAmount($deposit->final_amount, currencyFormat: false),
            'amount'          => showAmount($deposit->amount, currencyFormat: false),
            'charge'          => showAmount($deposit->charge, currencyFormat: false),
            'rate'            => showAmount($deposit->rate, currencyFormat: false),
            'trx'             => $deposit->trx,
        ]);

        $sells = Sell::where('code', $deposit->code)->get();

        foreach ($sells as $item) {
            $item->status         = Status::PROCESSING;
            $item->payment_status = Status::SELL_PAYMENT_PAID;
            $item->save();

            $item->product->total_sell += $item->qty;
            $item->product->save();
        }

        // Send order status update email to customer
        if (isset($deposit->shipping['email']) && !empty($deposit->shipping['email'])) {
            try {
                $sellsWithProduct = Sell::where('code', $deposit->code)->with('product')->get();
                $totalAmount = $sellsWithProduct->sum('total_price');
                
                \Illuminate\Support\Facades\Log::info('Sending approval email to: ' . $deposit->shipping['email']);
                
                \Illuminate\Support\Facades\Mail::to($deposit->shipping['email'])->send(
                    new \App\Mail\OrderStatusUpdate($deposit, $sellsWithProduct, $totalAmount, 'Approved', '#10b981')
                );
                
                \Illuminate\Support\Facades\Log::info('Approval email sent successfully');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send approval email: ' . $e->getMessage());
            }
        } else {
            \Illuminate\Support\Facades\Log::warning('No PayPal email found for approved order: ' . $deposit->code);
        }

        $notify[] = ['success', 'Payment request has been approved.'];

        return redirect()->route('admin.deposit.pending')->withNotify($notify);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'message' => 'required|string|max:255'
        ]);
        $deposit = Deposit::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        $deposit->admin_feedback = $request->message;
        $deposit->status = Status::PAYMENT_REJECT;
        $deposit->save();

        notify($deposit->user, 'PAYMENT_REJECT', [
            'method_name'       => $deposit->methodName(),
            'method_currency'   => $deposit->method_currency,
            'method_amount'     => showAmount($deposit->final_amount, currencyFormat: false),
            'amount'            => showAmount($deposit->amount, currencyFormat: false),
            'charge'            => showAmount($deposit->charge, currencyFormat: false),
            'rate'              => showAmount($deposit->rate, currencyFormat: false),
            'trx'               => $deposit->trx,
            'rejection_message' => $request->message
        ]);

        $sells = Sell::where('code', $deposit->code)->get();

        foreach ($sells as $item) {
            $item->status = Status::REJECTED;
            $item->payment_status = Status::SELL_PAYMENT_REJECTED;
            $item->save();
        }

        // Send order status update email to customer
        if (isset($deposit->shipping['email']) && !empty($deposit->shipping['email'])) {
            try {
                $sellsWithProduct = Sell::where('code', $deposit->code)->with('product')->get();
                $totalAmount = $sellsWithProduct->sum('total_price');
                
                \Illuminate\Support\Facades\Log::info('Sending rejection email to: ' . $deposit->shipping['email']);
                
                \Illuminate\Support\Facades\Mail::to($deposit->shipping['email'])->send(
                    new \App\Mail\OrderStatusUpdate($deposit, $sellsWithProduct, $totalAmount, 'Rejected', '#ef4444')
                );
                
                \Illuminate\Support\Facades\Log::info('Rejection email sent successfully');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send rejection email: ' . $e->getMessage());
            }
        } else {
            \Illuminate\Support\Facades\Log::warning('No PayPal email found for rejected order: ' . $deposit->code);
        }

        $notify[] = ['success', 'Payment request rejected successfully'];
        return  to_route('admin.deposit.pending')->withNotify($notify);
    }
}
