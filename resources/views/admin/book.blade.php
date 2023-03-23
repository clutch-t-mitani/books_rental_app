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
                            <a href="{{ route('admin.index') }}" class="link"><i class="fa fa-pen"z></i>レンタル管理ページへ</a><br>
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
                                            <option value="0">未選択</option>
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
                    <button type="submit" class="btn btn-outline-primary" style="margin-bottom: 10px"  data-toggle="modal" data-target="#create_book">本の新規登録</button>
                    {{-- 本の新規登録 --}}
                    @include('admin.modal.book_create')

                    <button type="submit" class="btn btn-outline-success" style="margin-bottom: 10px"  data-toggle="modal" data-target="#edit_categories">カテゴリ編集</button>
                    {{-- カテゴリの編集 --}}
                    @include('admin.modal.edit_categories')

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
                            @include('admin.modal.book_edit')
                            {{-- 本の削除 --}}
                            @include('admin.modal.book_delete')
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
        $.ajax(
        {
            url: "/",
            type: "GET",
            data: {
                "search_word": search_word,
                "category_id": category_id,
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
</script>


<style>
.link{
    color: white;

}
.link:hover{
    text-decoration: underline;
    color: white;
}
</style>
@endsection

