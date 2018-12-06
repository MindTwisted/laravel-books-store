<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cart';

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the book that owns the cart.
     */
    public function book()
    {
        return $this->belongsTo('App\Book');
    }
}
