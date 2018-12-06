<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
     /**
     * Get the order that owns the order detail.
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

     /**
     * Get the book that owns the order detail.
     */
    public function book()
    {
        return $this->belongsTo('App\Book');
    }
}
