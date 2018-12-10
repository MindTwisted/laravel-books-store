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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'count',
        'user_id',
        'book_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'book_id',
        'created_at',
        'updated_at',
    ];

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
