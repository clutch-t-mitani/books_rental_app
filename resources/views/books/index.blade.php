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
                            <th>カテゴリー</th>
                            <th>タイトル</th>
                            <th>作者</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td></td>
                                <td>{{ $book->name }}</td>
                                <td>{{ $book->author }}</td>
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
