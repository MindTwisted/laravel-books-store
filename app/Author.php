<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /**
     * The books that belong to the author.
     */
    public function books()
    {
        return $this->belongsToMany('App\Book');
    }
}
