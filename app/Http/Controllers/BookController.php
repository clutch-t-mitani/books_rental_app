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
        $books_categories = [];
        foreach ($books as $key => $book) {
            $categories = Book::find($book['id'])->categories()->pluck('name')->toArray();
            $books_categories[$book['id']] = $categories;
        }
        return view('books.index',compact('books','books_categories'));
    }
}
