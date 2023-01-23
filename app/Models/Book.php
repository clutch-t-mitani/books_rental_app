<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Database\Eloquent\SoftDeletes;


class Book extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'author',
    ];

    // public function categories()
    // {
    //     return $this->belongsToMany('App\Models\Category');
    // }

    //中間テーブルとつなぐ
    public function rental_statuses()
    {
        // return $this->hasMany('App\Models\RentalStatus');
        return $this->hasMany('App\Models\RentalStatus');
    }

    //中間テーブルとつなぐ
    public function book_categories()
    {
        return $this->hasMany('App\Models\BookCategory');
    }


}
