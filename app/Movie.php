<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Movie extends Model
{
    protected $fillable = [
        'themoviedb_id', 'portrait_image', 'landscape_image','imdb' ,'release_date' ,'overview','runetime','genres','awards','rotten_tomato'
    ];
}
