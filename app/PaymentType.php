<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    /**
     * Get the orders for the payment type.
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
