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
            $categories = Book::findOrFail($book['id'])->categories()->pluck('name')->toArray();
            $books_categories[$book['id']] = $categories;
        }

        $rental_statuses= [];
        foreach ($books as $key => $book) {
            $rental_start_datetime = Book::find($book['id'])->users()->pluck('rental_start_datetime')->toArray();
            $return_datetime = Book::find($book['id'])->users()->pluck('return_datetime')->toArray();
            if (!empty($rental_start_datetime)) {
                $rental_statuses[$book['id']]['rental_start_datetime'] = date('Y/n/j/(D)', strtotime($rental_start_datetime[0])) ;
                $rental_statuses[$book['id']]['rental_status'] = empty($return_datetime[0]) ? 'レンタル中' : null;
            }
        }

        dd($rental_statuses);
        return view('books.index',compact('books','books_categories'));
    }
}
