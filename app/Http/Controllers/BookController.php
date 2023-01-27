<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookCategory;
use App\Models\RentalStatus;

class BookController extends Controller
{
    public function index(Request $request)
    {

       $session_data = $request->session()->get('session_data');

        $categories = Category::get();

        $search_word = $request->search_word; //本の名前
        $category_id = $request->category_id; //カテゴリー
        $rental_status = $request->rental_status; //レンタル状況

        $query = Book::query();
        if (isset($search_word)) {
            $query->where('name', 'like', '%' . self::escapeLike($search_word) . '%')->orWhere('author', 'like', '%' . self::escapeLike($search_word) . '%');
        }

        if (isset($category_id)) {
            $query->whereHas('book_categories', function($q) use($category_id)  {
                $q->where('book_category.category_id', $category_id);
            });
        }

        if ($rental_status == 2) {
            $query->where('is_rentable', 1);
        }

        $books = $query->paginate(10);

        return view('books.index',compact('books','categories','search_word','rental_status','category_id'));
    }

    public function store(Request $request)
    {
        $book = Book::findOrFail($request->book_id);

        try {
            DB::beginTransaction();
            if ($book->is_rentable) {
                $book->is_rentable = false;
                $book->save();

                $rental_status = new RentalStatus();
                $rental_status->user_id = Auth::id();
                $rental_status->book_id = $request->book_id;
                $rental_status->rental_start_datetime = now();
                $rental_status->save();
            } else {
                return redirect('/')->with('msg_danger', 'レンタルできませんでした');
            }
            DB::commit();
            return redirect('/')->with('msg_success', 'レンタルしました');

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect('/')->with('msg_danger', 'レンタルできませんでした');
        }

    }

    public static function escapeLike($str)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $str);
    }
}
