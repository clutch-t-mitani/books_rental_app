<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RentalStatus;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class AdminRankingController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        $rental_statuses = RentalStatus::get();
        $rental_count_list = DB::table('rental_statuses')
                        ->select('book_id')
                        ->selectRaw('COUNT(book_id) as count_book_id')
                        ->groupBy('book_id')
                        ->orderBy('count_book_id', 'desc')
                        ->get();

        return view('admin.ranking',compact('categories','rental_count_list'));
    }
}
