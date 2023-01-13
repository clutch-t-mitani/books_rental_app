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
        $day_of_week = ['日', '月', '火', '水', '木', '金', '土'];
        return view('user.index',compact('user','day_of_week'));

    }
}
