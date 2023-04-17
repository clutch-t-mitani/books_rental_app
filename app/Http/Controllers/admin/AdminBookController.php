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
use App\Http\Requests\RegisterBookRequest;

class AdminBookController extends Controller
{
    public function index(BookSearchRequest $request)
    {
        $categories = Category::get();
        $book_categories = BookCategory::get();
        $search_word = $request->search_word; //本の名前
        $category_id = $request->category_id; //カテゴリー

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

        $books = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.book',[
            'pagenate_params' => [
                'search_word' => $search_word,
                'category_id' => $category_id,
            ],
        ],compact('categories','search_word','category_id','books','book_categories'));
    }

    public function create(RegisterBookRequest $request)
    {
        try {
            DB::beginTransaction();
            $book = new Book();
            $book->name = $request->name;
            $book->author = $request->author;
            $book->save();


           if (isset($request->categories_id)) {
                $book->categories()->sync($request->categories_id);
            }

            DB::commit();
            session()->flash('msg_success', '登録に成功しました');
            return redirect('/admin/books');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('msg_danger', '登録に失敗しました。');
            return redirect('/admin/books');
        }
    }

    public function update(RegisterBookRequest $request)
    {
        try {
            DB::beginTransaction();
            $book = Book::findOrFail($request->id);
            $book->name = $request->name;
            $book->author = $request->author;
            $book->save();

           if (isset($request->categories_id)) {
                $book->categories()->sync($request->categories_id);
            } else {
                BookCategory::where('book_id',$request->id)->delete();
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

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $book = Book::findOrFail($request->id);

            if ($book->is_rentable) {
                $book->delete();
                BookCategory::where('book_id',$book->id)->delete();
                RentalStatus::where('book_id',$book->id)->delete();
            } else {
                throw new \Exception();
            }
            DB::commit();
            session()->flash('msg_success', '削除しました。');
            return redirect('/admin/books');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('msg_danger', '削除に失敗しました。');
            return redirect('/admin/books');
        }
    }

    public static function escapeLike($str)
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $str);
    }

}
