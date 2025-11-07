<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Sell extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class,'code','code');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeProcessing($query)
    {
        $query->where('status', Status::PROCESSING);
    }

    public function scopeDelivered($query)
    {
        $query->where('status', Status::DELIVERED);
    }

    public function scopePending($query)
    {
        $query->where('status', Status::PENDING);
    }

    public function scopeRejected($query)
    {
        $query->where('status', Status::REJECTED);
    }

    public function paymentStatusBadge(): Attribute
    {
        return new Attribute(function () {

            $html = '';
            if ($this->payment_status == Status::SELL_PAYMENT_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } elseif ($this->payment_status == Status::SELL_PAYMENT_PAID) {
                $html = '<span class="badge badge--success">' . trans('Paid') . '</span>';
            } else {
                $rejectedBtn = $this->deposit->admin_feedback ? '<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Rejected Reason" class="badge badge--warning rejectBtn" data-rejected_reason='.json_encode($this->deposit->admin_feedback).'><i class="las la-exclamation-circle"></i></span>' : '';
                $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>'. ' '.$rejectedBtn;
            }
            return $html;
        });
    }

    public function sellStatusBadge(): Attribute
    {
        return new Attribute(function () {

            $html = '';
            if ($this->status == Status::PROCESSING) {
                $html = '<span class="badge badge--info">' . trans('Processing') . '</span>';
            } elseif ($this->status == Status::DELIVERED) {
                $html = '<span class="badge badge--success">' . trans('Delivered') . '</span>';
            } elseif ($this->status == Status::PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } else {
                $html = '<span class="badge badge--danger">' . trans('Rejected') . '</span>';
            }
            return $html;
        });
    }
}
