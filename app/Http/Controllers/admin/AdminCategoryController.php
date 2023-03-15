<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookCategory;
use App\Models\RentalStatus;

class AdminCategoryController extends Controller
{
    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->save();

        session()->flash('msg_success', '新規登録しました。');
        return redirect('/admin/books');
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->category_action == 'edit') {
                $category = Category::findOrFail($request->category_id);
                $category->name = $request->name;
                $category->save();
            } elseif ($request->category_action == 'delete') {
                Category::where('id',$request->category_id)->delete();
                BookCategory::where('category_id',$request->category_id)->delete();
            } else {
                throw new \Exception();
            }
            DB::commit();
            session()->flash('msg_success', '削除しました');
            return redirect('/admin/books');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('msg_danger', '削除に失敗しました。');
            return redirect('/admin/books');
        }
    }
}
