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
        return view('user.index',compact('user'));
    }
}
