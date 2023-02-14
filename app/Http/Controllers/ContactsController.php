<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactsSendmail;

class ContactsController extends Controller
{
    public function index()
    {
        // 入力ページを表示
        return view('contact.index');
    }

    public function confirm(Request $request)
    {
        // バリデーションルールを定義
        // 引っかかるとエラーを起こしてくれる
        $request->validate([
        'email' => 'required|email',
        'title' => 'required',
        'body' => 'required',
        ]);

        // フォームからの入力値を全て取得
        $inputs = $request->all();

        // 確認ページに表示
        // 入力値を引数に渡す
        return view('contact.confirm', [
        'inputs' => $inputs,
        ]);
    }

    public function send(Request $request)
    {
        // バリデーション
        $request->validate([
        'email' => 'required|email',
        'title' => 'required',
        'body' => 'required'
        ]);

        // actionの値を取得
        $action = $request->input('action');

        // action以外のinputの値を取得
        $inputs = $request->except('action');

        //actionの値で分岐
        if($action !== 'submit'){

            // 戻るボタンの場合リダイレクト処理
            return redirect()
            ->route('contact.index')
            ->withInput($inputs);

        } else {
            // 送信ボタンの場合、送信処理

            // ユーザにメールを送信
            \Mail::to($inputs['email'])->send(new ContactsSendmail($inputs));
            // 自分にメールを送信
            \Mail::to('3reps.mitani@gmail.com')->send(new ContactsSendmail($inputs));

            // 二重送信対策のためトークンを再発行
            $request->session()->regenerateToken();

            // 送信完了ページのviewを表示
            return view('contact.thanks');

        }
    }
}
