<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use HasFactory;
    
    protected $table = 'movies';

    protected $guarded = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'title', 
        'summary', 
        'cover_image', 
        'user_id',
        'genre_id',
        'author_id'   
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tags(){
        return $this->belongsToMany('App\Models\Tag');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment');
    }

    public function ratings(){
        return $this->hasMany('App\Models\Rating');
    }

    public function author(){
        return $this->belongsTo('App\Models\Author');
    }

    public function genre(){
        return $this->belongsTo('App\Models\Genre');
    }

    
}
