<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\BookCategory;
use App\Models\RentalStatus;
use GuzzleHttp\Client;
use App\Http\Requests\CartRequest;
use App\Http\Requests\RentalBookRequest;

class CartController extends Controller
{
    //カートに一覧
    public function index(Request $request)
    {
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
    public function add(CartRequest $request)
    {
        $book_id = $request->book_id;
        $book = Book::findOrFail($book_id);

        try {
            DB::beginTransaction();
            if ($book->is_rentable) {
                $session_data = [];
                $session_data = compact('book_id');
                $request->session()->push('session_data', $session_data);
            } else {
                throw new \Exception();
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
    public function delete(CartRequest $request)
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

    //レンタルする
    public function store(RentalBookRequest $request)
    {
        $rental_books = $request->book_id;

        $success_rental_books = [];
        $failure_rental_books = [];

        $sessinon_datas = $request->session()->get('session_data');

        foreach ($rental_books as $key => $book_id) {
            $rental_book = Book::findOrFail($book_id);
            try {
                DB::beginTransaction();
                if ($rental_book->is_rentable) {
                    $rental_book->is_rentable = false;
                    $rental_book->save();

                    $rental_status = new RentalStatus();
                    $rental_status->user_id = Auth::id();
                    $rental_status->book_id = $rental_book->id;
                    $rental_status->rental_start_datetime = now();
                    $rental_status->save();
                    $success_rental_books[$rental_book->id] = $rental_book->name;
                } else {
                    $failure_rental_books[$rental_book->id]= $rental_book->name;
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                $failure_rental_books[$rental_book->id]= $rental_book->name;
            }
            $request->session()->forget('session_data');
            DB::commit();
        }

        if ($success_rental_books) {
            $success_books = implode("、", $success_rental_books);
            $succes_message = $success_books.'をレンタルしました。';
            $rental_user = User::find(Auth::id())->name;

            $token = config('pj.define.chatwork.api_token');    // 取得したAPIトークン
            $room_id = "310080596";     // 取得したルームID
            $url = "https://api.chatwork.com/v2/rooms/{$room_id}/messages";
            $body = 'レンタル者:'.$rental_user.'様'."\n".$succes_message;

            Book::chat_work($token,$room_id,$url,$body);
            session()->flash('msg_success', $succes_message);
        }

        if (!empty($failure_rental_books)) {
            $failure_books = implode("、", $failure_rental_books);
            $failure_message = $failure_books.'がレンタルできませんでした。';
            session()->flash('msg_danger', $failure_message);
        }

        return redirect('/');

    }
}
