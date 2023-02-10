@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color: #00FF00; display: flex; justify-content: space-between;">
                    <div style="display: inline-block;">レンタル済み商品一覧</div>
                    @if( Auth::check() )
                        <div style="display: inline-block; ">
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <!--検索フォーム-->
                    <div class="row">
                        <div class="col-sm">
                            <form method="GET" action="{{ route('admin.index') }}" >
                                <div class="form-group row">
                                <label class="col-sm-2 col-form-label" style="font-size: 0.95em;">タイトル or 作者 <br>or レンタル者</label>
                                    {{-- <label class="col-sm-2 col-form-label" style="font-size: 0.95em; padding-top:0px">
                                        <select class="form-select">
                                            <option value="">タイトル</option>
                                            <option value="">作者</option>
                                            <option value="">レンタル者</option>
                                        </select>
                                    </label> --}}
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
                                                <option value="1" >全て</option>
                                                <option value="2" @if($rental_status==2) selected @endif>レンタル中</option>
                                                <option value="3" @if($rental_status==3) selected @endif>返却済</option>
                                                <option value="4" @if($rental_status==4) selected @endif>返却期日超え</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary" id="search_button">絞り込み</button>
                                            <a href="/admin/rental_books">クリア</a>
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if ($due_return_date->isNotEmpty())
                        <div style="color:red">
                            <i class="fas fa-exclamation-triangle"></i>返却期日超えの本が{{ count($due_return_date)}}冊あります<i class="fas fa-exclamation-triangle"></i>
                        </div>
                    @endif
                    <table id="table1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10%">レンタル者</th>
                                <th style="width: 20%">カテゴリー</th>
                                <th style="width: 30%">タイトル</th>
                                <th style="width: 15%">作者</th>
                                <th style="width: 15%">レンタル日<br>返却期日</th>
                                <th style="width: 10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($rentaled_book_statues as $rentaled_book_status)
                            <tr style="{{ $due_return_date->whereIn('id',$rentaled_book_status->id)->isNotEmpty()? "color: red;": "" }}">
                                <td>{{ $rentaled_book_status->user->name  }}</td>
                                <td>
                                    @foreach ($rentaled_book_status->book->book_categories as $book_category)
                                        【{{ $book_category->category->name }}】<br>
                                    @endforeach
                                </td>
                                <td>{{ $rentaled_book_status->book->name }}</td>
                                <td>{{ $rentaled_book_status->book->author }}</td>
                                <td>
                                    {{ $rentaled_book_status->rental_start_datetime->isoFormat('YYYY/MM/DD(ddd)') }}<br>
                                    {{ $rentaled_book_status->rental_start_datetime->addDays(7)->isoFormat('YYYY/MM/DD(ddd)') }}
                                </td>
                                <td>
                                    @if (is_null($rentaled_book_status->return_datetime))
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#returnButtton{{ $rentaled_book_status->id }}">返却</button>
                                    @else
                                        返却済
                                    @endif
                                </td>
                            </tr>
                            <div class="modal fade" id="returnButtton{{ $rentaled_book_status->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.update') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="status_id" value="{{ $rentaled_book_status->id }}" >
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">【{{ $rentaled_book_status->book->name }}】返却登録しますか？</h4>
                                            </div>
                                            <div class="modal-body">
                                                <label>レンタル者：  {{ $rentaled_book_status->user->name }} 様</label><br>
                                                <label>返却期日：  {{ $rentaled_book_status->rental_start_datetime->addDays(7)->isoFormat('YYYY年MM月DD(ddd)') }}</label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                                <button type="submit" class="btn btn-primary">返却登録する</button>
                                        </form>
                                        </div>
                                    </div>
                                </div>

                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $rentaled_book_statues->links() }}
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

