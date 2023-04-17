<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\View;

class Book extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'author',
    ];

    //Categoryとリレーション
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category')
        ->withTimestamps();
    }

    //中間テーブルとリレーション
    public function rental_statuses()
    {
        return $this->hasMany('App\Models\RentalStatus');
    }

    //中間テーブルとリレーション
    public function book_categories()
    {
        return $this->hasMany('App\Models\BookCategory');
    }

    /**
     * chatwork連携 メッセージ送信
     * param string $token 取得したAPIトークン
     * param string $room_id  取得したルームID
     * param string $url チャットワークurl
     * param string $body chatwork送信内容
     */
    public static function chat_work($token,$room_id,$url,$body)
    {
        $client = new Client();
        $client->post($url, [
            'headers' => ['X-ChatWorkToken' => $token],
            'form_params' => ['body' => $body],
        ]);
    }

    /**
     * レンタル商品一覧に過去のレンタル数の値を含める。
     * param array $books レンタル商品一覧
     * return array $sorted_ranking_books 過去のレンタル数を含めたレンタル商品一覧（レンタル数が多い方順にソート）
     */
    public static function ranking($books)
    {
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

        $sorted_ranking_books = $books->sortByDesc('rental_count');
        return $sorted_ranking_books;
    }


}
