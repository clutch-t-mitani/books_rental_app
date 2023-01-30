@extends('layouts.app_book')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color: #000066; color: white; display: flex; justify-content: space-between;">
                    <div style="display: inline-block;">レンタル商品一覧</div>
                    @if( Auth::check() )
                        <div style="display: inline-block; "><a href="{{ url('/mypage') }}" class="user-link"><i class="fas fa-user"></i>マイページへ</a></div>
                    @endif
                </div>
                <div class="card-body">
                    {{-- フラッシュメッセージ始まり --}}
                    {{-- 成功の時 --}}
                    @if (session('successMessage'))
                        <div class="alert alert-success text-center">
                            {{ session('successMessage') }}
                        </div>
                    @endif
                    {{-- 失敗の時 --}}
                    @if (session('errorMessage'))
                        <div class="alert alert-danger text-center">
                            {{ session('errorMessage') }}
                        </div>
                    @endif
                    {{-- フラッシュメッセージ終わり --}}
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
                                                <option value="">未選択</option>
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
                                            {{-- <button type="submit" class="btn btn-link" id="search_clear">クリア</button> --}}
                                            <a href="/">クリア</a>
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>

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
                                    @foreach ($book->rental_statuses as $rental_status)
                                        @if (empty($rental_status->return_datetime))
                                            {{ $rental_status->rental_start_datetime->addDays(7)->isoFormat('YYYY/MM/DD(ddd)') }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <div class="modal fade" id="rentalButtton{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('cart.add') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}" >
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">【{{ $book->name }}】レンタルしますか？</h4>
                                            </div>
                                            <div class="modal-body">
                                                <label>返却予定日 ：{{ now()->addDays(7)->isoFormat('YYYY年MM月DD(ddd)') }}</label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                                <button type="submit" class="btn btn-primary">カートに入れる</button>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $books->links() }}
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

    // $('#search_clear').on('click', function () {
    //     search_word = "";
    //     category_id =  "";
    //     rental_status = 1;
    //     $.ajax(
    //     {
    //         url: "/",
    //         type: "GET",
    //         data: {
    //             "search_word": search_word,
    //             "category_id": category_id,
    //             "rental_status": rental_status,
    //         },
    //         dataType: 'json',
    //     })
    //     //通信が成功したとき
    //     .done((res)=>{
    //         console.log(res.message)
    //     })
    //     //通信が失敗したとき
    //     .fail((error)=>{
    //         console.log(error.statusText)
    //     })
    // });


    // var input_name = document.getElementById("search_word");
    // input_name.addEventListener("input",function(){
    //     const search_word = document.getElementsByName('search_word')[0].value;
    //     const category_id = document.getElementsByName('category_id')[0].value;
    //     const rental_status = document.getElementsByName('rental_status')[0].value;
    //     console.log(search_word);
    //     $.ajax(
    //     {
    //         url: "/",
    //         type: "GET",
    //         data: {
    //             "search_word": search_word,
    //             "category_id": category_id,
    //             "rental_status": rental_status,
    //         },
    //         dataType: 'json',
    //     })
    //     //通信が成功したとき
    //     .done((res)=>{
    //         console.log(res.message)
    //     })
    //     //通信が失敗したとき
    //     .fail((error)=>{
    //         console.log(error.statusText)
    //     })
    // });



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

