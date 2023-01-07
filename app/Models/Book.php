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

    public function rental_statuses()
    {
        return $this->belongsToMany(User::class,'rental_statuses')->withPivot('rental_start_datetime','return_datetime');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }


}
