<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
     /**
     * The books that belong to the genre.
     */
    public function books()
    {
        return $this->belongsToMany('App\Book');
    }
}
