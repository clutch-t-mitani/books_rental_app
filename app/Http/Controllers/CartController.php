<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookCategory;
use App\Models\RentalStatus;

class CartController extends Controller
{
    //カートに一覧
    public function index(Request $request)
    {
    //    $user_id = Auth::id();
       $session_data = [];
       $session_data = $request->session()->get('session_data');

       $in_cart_books = [];
       if (isset($session_data)) {
           foreach ($session_data as $value) {
                   $in_cart_books[] = Book::findOrFail($value['book_id']);
           }
       }

       return view('cart.index',compact('in_cart_books'));
    }

    //カートに追加
    public function add(Request $request)
    {
        $book_id = $request->book_id;
        $book = Book::findOrFail($book_id);

        try {
            DB::beginTransaction();
            if ($book->is_rentable) {
                $book->is_rentable = false;
                $book->save();

                $Session_data = [];
                $session_data = compact('book_id');
                $request->session()->push('session_data', $session_data);
                $is_messeage_type = true;
            } else {
                session()->flash('msg_danger', 'カートに追加できませんでした');
                return redirect('/');
            }
            DB::commit();
            session()->flash('msg_success', 'カートに追加しました');
            return redirect('/cart');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('msg_danger', 'カートに追加できませんでした');
            return redirect('/');
        }
    }

    //カートから削除
    public function delete(Request $request)
    {
        $sessinon_datas = $request->session()->get('session_data');

        //カートから削除したbook_idのkeyを取得
        $delete_key = null;
        foreach ($sessinon_datas as $key => $sessinon_data) {
            if (in_array($request->book_id, $sessinon_data)) {
                $delete_key = $key ;
            }
        }

        $request->session()->forget('session_data.'.$delete_key);
        return redirect('/cart');
    }
}
