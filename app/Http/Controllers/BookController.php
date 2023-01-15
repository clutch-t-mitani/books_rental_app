<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::get();
        $day_of_week = ['日', '月', '火', '水', '木', '金', '土'];
        return view('books.index',compact('books','day_of_week'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
