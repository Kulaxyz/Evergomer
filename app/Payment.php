<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use CrudTrait;

    protected $fillable = ['type', 'amount', 'user_id', 'invoice_id', 'paid_at', 'payment_method'];
    protected $dates = ['paid_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function userLink()
    {
        $name = $this->user->name;
        if (backpack_user()->can('view_users') || backpack_user()->can('edit_users')) {
            $link = '/cabinet/user/' . $this->user->id . '/show';
            return "<a href=" . $link . ">$name</a>";
        }
        return $name;
    }
}
