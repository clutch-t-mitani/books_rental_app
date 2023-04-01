<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 返却期日を超過したレンタルステータスの一覧
     */
    public function scopeIsOverReturnDate(Builder $query)
    {
        return $query->whereNull('return_datetime')->where('rental_statuses.rental_start_datetime', '<' ,now()->subDay(8));
    }

    public function scopeUserId(Builder $query ,$user_id)
    {
        return $query->where('rental_statuses.user_id', $user_id);
    }


}
