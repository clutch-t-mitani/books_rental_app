<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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

        $query = Book::query();
        if (isset($search_word)) {
            $query->where('name', 'like', '%' . self::escapeLike($search_word) . '%')->orWhere('author', 'like', '%' . self::escapeLike($search_word) . '%');
        }

        if (isset($category_id)) {
            $query->whereHas('book_categories', function($q) use($category_id)  {
                $q->where('book_category.category_id', $category_id);
            });
        }

        $books = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.book',compact('categories','search_word','category_id','books','book_categories'));
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $book = new Book();
            $book->name = $request->name;
            $book->author = $request->author;
            $book->save();


           if (isset($request->categories_id)) {
                foreach ($request->categories_id as $category_id) {
                    $book_category = new BookCategory();
                    $book_category->book_id = $book->id;
                    $book_category->category_id = $category_id;
                    $book_category->save();
                }
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
                foreach ($request->categories_id as $category_id) {
                    $book_category = new BookCategory();
                    $book_category->book_id = $request->id;
                    $book_category->category_id = $category_id;
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

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            Book::where('id',$request->id)->delete();
            BookCategory::where('book_id',$request->id)->delete();

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
