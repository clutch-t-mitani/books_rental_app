<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        //レンタル中の本一覧
        $rental_statues = $user->rental_statuses->whereNull('return_datetime')->sortBy('rental_start_datetime');
        //期日超えの本
        $due_return_date = $rental_statues->where('rental_start_datetime', '<' ,now()->subDay(8));
        return view('user.index',compact('user','rental_statues','due_return_date'));
    }
}
