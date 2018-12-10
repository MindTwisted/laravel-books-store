<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'discount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * The authors that belong to the book.
     */
    public function authors()
    {
        return $this->belongsToMany('App\Author');
    }

    /**
     * The genres that belong to the book.
     */
    public function genres()
    {
        return $this->belongsToMany('App\Genre');
    }

    /**
     * Get the order details for the book.
     */
    public function orderDetails()
    {
        return $this->hasMany('App\OrderDetail');
    }

    /**
     * Get the cart for the book.
     */
    public function cart()
    {
        return $this->hasMany('App\Cart');
    }
}
