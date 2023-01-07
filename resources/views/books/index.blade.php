@extends('layouts.app_book')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header">レンタル商品一覧</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table id="table1" class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 25%">カテゴリー</th>
                            <th style="width: 30%">タイトル</th>
                            <th style="width: 25%">作者</th>
                            <th style="width: 10%">レンタル状況</th>
                            <th style="width: 10%">返却日</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>
                                    @foreach ($books_categories[$book->id] as $category)
                                        【{{ $category }}】<br>
                                    @endforeach
                                </td>
                                <td>{{ $book->name }}</td>
                                <td>{{ $book->author }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
