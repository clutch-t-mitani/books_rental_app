<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
    ];

    //Bookとリレーション
    public function books()
    {
        return $this->belongsToMany('App\Models\Book')
        ->withTimestamps();
    }

    //中間テーブルとつなぐ
    public function book_categories()
    {
        return $this->hasMany('App\Models\BookCategory');
    }
}
