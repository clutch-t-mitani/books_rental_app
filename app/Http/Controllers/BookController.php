<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Category;
use App\Models\RentalStatus;

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
        DB::beginTransaction();
        try {
            $book = Book::findOrFail($request->book_id);
            if ($book->is_rental) {
                $book->is_rental = false;
                $book->save();

                $rental_status = new RentalStatus();
                $rental_status->user_id = Auth::id();
                $rental_status->book_id = $request->book_id;
                $rental_status->rental_start_datetime = now();
                $rental_status->save();
                DB::commit();
                return redirect('/')->with('flash_message', '登録しました');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/')->with('flash_message', '登録に失敗しました');
        }

    }
}
