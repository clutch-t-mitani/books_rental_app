<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
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

    //中間テーブルとつなぐ
    public function rental_statuses()
    {
        return $this->hasMany('App\Models\RentalStatus');
    }

    //中間テーブルとつなぐ
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


}
