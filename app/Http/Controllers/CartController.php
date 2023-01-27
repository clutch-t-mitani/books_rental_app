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
    public function index(Request $request)
    {
       $user_id = Auth::id();
       $session_data = [];
       $session_data = $request->session()->get('session_data');

       $in_cart_books = [];
       if (isset($session_data)) {
           foreach ($session_data as $value) {
               if ($value['user_id'] == $user_id) {
                   $in_cart_books[] = Book::findOrFail($value['book_id']);
               }
           }
       }

       return view('cart.index',compact('in_cart_books'));
    }

    public function add(Request $request)
    {
       $user_id = Auth::id();
       $book_id = $request->book_id;
       $Session_data = [];
       $session_data = compact('user_id', 'book_id');
       $request->session()->push('session_data', $session_data);

       return redirect('/');
    }

    public function delete(Request $request)
    {
        $session_data = [];
        $session_data = $request->session()->get('session_data');

        foreach ($session_data  as $key => $value) {
            if ($value['book_id'] == $request->book_id) {
                unset($session_data[$key]);
            }
        }

        return redirect('/cart');
    }
}
