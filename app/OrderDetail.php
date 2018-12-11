<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_title',
        'book_count',
        'book_price',
        'book_discount',
        'book_id',
        'order_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

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
