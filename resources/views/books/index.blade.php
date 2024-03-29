@extends('layouts.app_book')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @php
            //返却期日超えの本があるか確認
            $is_overdate_book = false;
            if (Session::pull('is_overdate_book')) {
                $is_overdate_book = true;
            }
        @endphp
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color: #000066; color: white; display: flex; justify-content: space-between;">
                    <div style="display: inline-block;">レンタル商品一覧</div>
                    @if( Auth::check() )
                        <div style="display: inline-block; ">
                            <a href="{{ route('user.mypage') }}" class="user-link"><i class="fas fa-user"></i> マイページへ</a><br>
                            <a href="{{ route('cart.index') }}" class="user-link"><i class="fas fa-shopping-cart"></i> カートへ （現在{{ count($in_cart_books) }} 冊）</a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <!--検索フォーム-->
                    <div class="row">
                        <div class="col-sm">
                            <form method="GET" action="{{ route('books.index') }}" >
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" style="font-size: 0.95em;">タイトル or 作者</label>
                                <!--入力-->
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" name="search_word" value="{{ $search_word }}" placeholder="検索ワードを入力してください">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-1" style="font-size: 0.95em;">商品<br>カテゴリ</label>
                                    <div class="col-3">
                                        <select name="category_id" class="form-control">
                                            <option value="0">未選択</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" @if($category->id == $category_id) selected @endif>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-1" style="font-size: 0.95em;">レンタル状況</label>
                                    <div class="col-3">
                                        <select name="rental_status" class="form-control" value="" >
                                            <option value="1">全て</option>
                                            <option value="2" @if($rental_status==2) selected @endif>レンタル可商品のみ</option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary" id="search_button">絞り込み</button>
                                        <a href="/">クリア</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-info btn-sm" style="margin-bottom: 5px" data-toggle="modal" data-target="#ranking">ランキングはこちら</button>
                    {{-- ランキングのモーダル --}}
                    @include('modal.ranking')
                    <table id="table1" class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 20%">カテゴリー</th>
                            <th style="width: 35%">タイトル</th>
                            <th style="width: 25%">作者</th>
                            <th style="width: 10%">状況</th>
                            <th style="width: 10%">返却予定日</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>
                                    @foreach ($book->book_categories as $book_category)
                                        【{{ $book_category->category->name }}】<br>
                                    @endforeach
                                </td>
                                <td>{{ $book->name }}</td>
                                <td>{{ $book->author }}</td>
                                <td style="text-align: center;">
                                    @if ($book->is_rentable && !in_array($book->id,$in_cart_books))
                                        <button type="button" class="btn btn-primary"
                                            @if( Auth::check() ) data-toggle="modal" data-target="#rentalButtton{{ $book->id }}"
                                            @else onclick="location.href = '{{ url('/login') }}'" @endif>借りる
                                        </button>
                                    @else
                                        <i class="fas fa-times"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (count($book->rental_statuses) && empty($book->rental_statuses[count($book->rental_statuses)-1]->return_datetime))
                                        {{ $book->rental_statuses[count($book->rental_statuses)-1]->rental_start_datetime->addDays(7)->isoFormat('YYYY/MM/DD(ddd)') }}
                                    @endif
                                </td>
                            </tr>
                            {{-- 借りるボタンのモーダル --}}
                            @include('modal.rental_book')
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $books->appends($pagenate_params)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#search_button').on('click', function () {
        const search_word = document.getElementsByName('search_word')[0].value;
        const category_id = document.getElementsByName('category_id')[0].value;
        const rental_status = document.getElementsByName('rental_status')[0].value;
        $.ajax(
        {
            url: "/",
            type: "GET",
            data: {
                "search_word": search_word,
                "category_id": category_id,
                "rental_status": rental_status,
            },
            dataType: 'json',
        })
        //通信が成功したとき
        .done((res)=>{
            console.log(res.message)
        })
        //通信が失敗したとき
        .fail((error)=>{
            console.log(error.statusText)
        })
    });
    //戻るボタンで戻ってきた際、強制リロード
    window.addEventListener('pageshow',()=>{
	    if(window.performance.navigation.type==2) location.reload();
    });

    //返却期日超えの本があればログイン時アラートを出す。
    var is_overdate_book = @json($is_overdate_book);
    if(is_overdate_book) {
        alert('返却期日を超過している本があります。\n\nマイページで詳細を確認してください。');
    }

</script>

<style>
.user-link{
    color: white;

}
.user-link:hover{
    text-decoration: underline;
    color: white;
}
</style>
@endsection

