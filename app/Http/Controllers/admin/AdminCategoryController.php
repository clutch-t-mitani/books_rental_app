<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookCategory;
use App\Models\RentalStatus;
use App\Http\Requests\CategoryRequest;


class AdminCategoryController extends Controller
{
    public function store(CategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->save();

        session()->flash('msg_success', '新規登録しました。');
        return redirect('/admin/books');
    }

    public function update(CategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            if ($request->category_action == 'edit') {
                $category = Category::findOrFail($request->category_id);
                $category->name = $request->name;
                $category->save();
                $msg_success = 'カテゴリ名を変更しました。';
            } elseif ($request->category_action == 'delete') {
                Category::where('id',$request->category_id)->delete();
                BookCategory::where('category_id',$request->category_id)->delete();
                $msg_success = 'カテゴリを削除しました。';
            } else {
                throw new \Exception();
            }
            DB::commit();
            session()->flash('msg_success', $msg_success);
            return redirect('/admin/books');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('msg_danger', '正しく変更されませんでした。');
            return redirect('/admin/books');
        }
    }
}
