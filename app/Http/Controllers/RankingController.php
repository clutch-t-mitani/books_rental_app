<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalStatus;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        $books = Book::get();
        $rental_count_list = DB::table('rental_statuses')
                        ->select('book_id')
                        ->selectRaw('COUNT(book_id) as rental_count')
                        ->groupBy('book_id')
                        ->get();

        $rental_count = [];
        foreach ($rental_count_list as $value) {
            $rental_count[$value->book_id] = $value->rental_count;
        }

        for ($i=0; $i < count($books); $i++) {
            if (array_key_exists($books[$i]['id'],$rental_count)) {
                $books[$i]['rental_count'] = $rental_count[$books[$i]['id']];
            } else {
                $books[$i]['rental_count'] = 0;
            }
        }

        $sorted_rental_books = $books->sortByDesc('rental_count')->values();
        return view('ranking',compact('categories','sorted_rental_books'));
    }

}
