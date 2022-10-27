<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $guarded = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'name',     
    ];

    public function movie()
    {
        return $this->belongsTo('App\Models\Movie');
    }
}
