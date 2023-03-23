<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookCategory;
use App\Models\RentalStatus;
use App\Http\Requests\BookSearchRequest;

class AdminRentalBookController extends Controller
{
    public function index(BookSearchRequest $request)
    {
        $categories = Category::get();
        $search_word = $request->search_word; //本の名前
        $category_id = $request->category_id; //カテゴリー
        $rental_status = $request->rental_status; //レンタル状況

        $query = RentalStatus::query();
        if ($search_word) {
            $query->where(function ($qry) use($search_word) {
                $qry->whereHas('book', function($q) use($search_word)  {
                    $q->where('books.name', 'like', '%' . self::escapeLike($search_word) . '%');
                })->orWhereHas('book', function($q) use($search_word)  {
                    $q->where('books.author', 'like', '%' . self::escapeLike($search_word) . '%');
                })->orWhereHas('user', function($q) use($search_word)  {
                    $q->where('users.name', 'like', '%' . self::escapeLike($search_word) . '%');
                });
            });
        }

        if ($category_id) {
            $query->whereHas('book', function($q) use($category_id)  {
                $q->whereHas('book_categories', function($q) use($category_id){
                    $q->where('book_category.category_id', $category_id);
                });
            });
        }

        if ($rental_status == 2) {
            $query->whereNull('return_datetime');
        } elseif ($rental_status == 3) {
            $query->whereNotNull('return_datetime');
        } elseif ($rental_status == 4) {
            $query->whereNull('return_datetime')->where('rental_start_datetime', '<' ,now()->subDay(8));
        }

        $rentaled_book_statues = $query->orderBy('rental_start_datetime', 'desc')->paginate(10);
        $over_date_books = RentalStatus::IsOverReturnDate()->get();

        return view('admin.index',[
                'pagenate_params' => [
                    'search_word' => $search_word,
                    'category_id' => $category_id,
                    'rental_status' => $rental_status,
                ],
            ],compact('categories','search_word','category_id','rental_status','rentaled_book_statues','over_date_books'));
    }

    //返却登録
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $rental_status = RentalStatus::findOrFail($request->status_id);
            $returned_book = Book::findOrFail($rental_status->book_id);
            $rental_status->return_datetime = now();
            $rental_status->save();

            if ($returned_book->is_rentable) {
                throw new \Exception();
            }

            $returned_book->is_rentable = true;
            $returned_book->save();

            DB::commit();
            session()->flash('msg_success', '返却登録完了しました。');
            return redirect('/admin/rental_books');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('msg_danger', '返却登録に失敗しました。');
            return redirect('/admin/rental_books');
        }
    }

    public static function escapeLike($str)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $str);
    }

}
