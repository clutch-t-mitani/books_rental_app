@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color: red; display: flex; justify-content: space-between;">
                    <div style="display: inline-block; color:white">本在庫一覧</div>
                    @if( Auth::check() )
                        <div style="display: inline-block; ">
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <!--検索フォーム-->
                    <div class="row">
                        <div class="col-sm">
                            <form method="GET" action="{{ route('admin.book') }}" >
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" style="font-size: 0.95em;">タイトル or 作者</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="search_word" value="{{ $search_word }}" placeholder="検索ワードを入力してください" style="font-size: 0.95em;">
                                    </div>
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
                                    <div class="col-auto" style=" float: right;">
                                        <button type="submit" class="btn btn-primary" id="search_button">絞り込み</button>
                                        <a href="/admin/books">クリア</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table id="table1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10%">登録日</th>
                                <th style="width: 20%">カテゴリー</th>
                                <th style="width: 25%">タイトル</th>
                                <th style="width: 25%">作者</th>
                                <th style="width: 30%"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>{{ $book->created_at->isoFormat('YYYY/MM/DD(ddd)') }}</td>
                                <td>
                                    @foreach ($book->book_categories as $book_category)
                                        【{{ $book_category->category->name }}】<br>
                                    @endforeach
                                </td>
                                <td>{{ $book->name }}</td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#edit{{ $book->id }}">編集</button>
                                    @if (count($book->rental_statuses) && !empty($book->rental_statuses[count($book->rental_statuses)-1]->return_datetime) ||
                                    count($book->rental_statuses) == 0)
                                        <button type="submit" class="btn btn-danger"  data-toggle="modal" data-target="#delete{{ $book->id }}">削除</button>
                                    @else
                                        <span style="color:red" >貸出中</span>
                                    @endif
                                </td>
                            </tr>
                                {{-- 本の編集 --}}
                                <div class="modal fade" id="edit{{ $book->id  }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.book.update') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $book->id }}" >
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel">本の内容を編集</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <label>タイトル</label>
                                                    <input type="text" class="form-control" name="name" value="{{ $book->name }}" placeholder="タイトルを入力してください" required>
                                                    <br>
                                                    <label>作者</label>
                                                    <input type="text" class="form-control" name="author" value="{{ $book->author }}" placeholder="作者を入力してください" required>
                                                    <br>
                                                    <label>カテゴリ</label>
                                                    @php
                                                        $subject_book_categories = [];
                                                        foreach ($book_categories as $book_category) {
                                                            if ($book_category->book_id == $book->id) {
                                                                array_push($subject_book_categories, $book_category->category_id);
                                                            }
                                                        }
                                                    @endphp
                                                    <select name="categories_id[]" class="form-control" multiple>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" @if(in_array($category->id,$subject_book_categories))selected @endif>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                                    <button type="submit" class="btn btn-primary">編集する</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- 本の削除 --}}
                                <div class="modal fade" id="delete{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.book.delete') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $book->id }}" >
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel">【{{ $book->name }}】を削除しますか？</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <label for="" style="color:red"><i class="fas fa-exclamation-triangle"></i>一度削除すると復元できません。</label>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                                    <button type="submit" class="btn btn-danger">削除する</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $books->render() }}
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

