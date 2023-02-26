<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookCategory;
use App\Models\RentalStatus;

class AdminBookController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::get();
        $book_categories = BookCategory::get();
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

        // if ($rental_status == 2) {
        //     $query->whereNull('return_datetime');
        // } elseif ($rental_status == 3) {
        //     $query->whereNotNull('return_datetime');
        // } elseif ($rental_status == 4) {
        //     $query->whereNull('return_datetime')->where('rental_start_datetime', '<' ,now()->subDay(8));
        // }

        $books = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.book',compact('categories','search_word','category_id','rental_status','books','book_categories'));
    }


    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $book = Book::findOrFail($request->id);
            $book->name = $request->name;
            $book->author = $request->author;
            $book->save();

           BookCategory::where('book_id',$request->id)->delete();

           if (isset($request->categories_id)) {
                foreach ($request->categories_id as $category) {
                    $book_category = new BookCategory();
                    $book_category->book_id = $request->id;
                    $book_category->category_id = $category;
                    $book_category->save();
                }
            }
            DB::commit();
            session()->flash('msg_success', '編集に成功しました');
            return redirect('/admin/books');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('msg_danger', '編集に失敗しました。');
            return redirect('/admin/books');
        }
    }

    public static function escapeLike($str)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $str);
    }

}
