<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'rental_start_datetime',
        'return_datetime',
    ];

    protected $dates = [
        'rental_start_datetime',
        'return_datetime',
    ];

    //例 book_id5はbooksテーブルに1つだから belongsTo でかつ関数名は単数系
    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
