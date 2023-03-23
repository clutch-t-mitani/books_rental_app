<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookCategory;
use App\Models\RentalStatus;
use App\Http\Requests\BookSearchRequest;

class BookController extends Controller
{
    public function index(BookSearchRequest $request)
    {
        $session_data = [];
        $session_data = $request->session()->get('session_data');
        $in_cart_books = [];
        if (!empty($session_data)) {
            foreach ($session_data as $key => $book_id) {
                array_push($in_cart_books,$book_id['book_id']);
            }
        }

        $categories = Category::get();
        $search_word = $request->search_word; //本の名前
        $category_id = $request->category_id; //カテゴリー
        $rental_status = $request->rental_status; //レンタル状況

        $query = Book::query();
        if ($search_word) {
            $query->where(function ($qry) use($search_word) {
                $qry->where('name', 'like', '%' . self::escapeLike($search_word) . '%')->orWhere('author', 'like', '%' . self::escapeLike($search_word) . '%');
            });
        }

        if ($category_id) {
            $query->whereHas('book_categories', function($q) use($category_id)  {
                $q->where('book_category.category_id', $category_id);
            });
        }

        if ($rental_status == 2) {
            $query->where('is_rentable', 1);
        }

        $books = $query->paginate(10);

        return view('books.index',[
            'pagenate_params' => [
                'search_word' => $search_word,
                'category_id' => $category_id,
                'rental_status' => $rental_status,
            ],
        ],compact('books','categories','search_word','rental_status','category_id','in_cart_books'));
    }

    public static function escapeLike($str)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $str);
    }
}
