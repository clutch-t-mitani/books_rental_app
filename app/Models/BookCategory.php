<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'category_id',
    ];

    //例 book_id5はbooksテーブルに1つだから belongsTo でかつ関数名は単数系
    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }


}
