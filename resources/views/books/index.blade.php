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
                            <th style="width: 10%">返却予定日</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>
                                    @foreach ($book->categories as $category)
                                        【{{ $category->name }}】<br>
                                    @endforeach
                                </td>
                                <td>{{ $book->name }}</td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    @foreach ($book->rental_statuses as $rental_status)
                                        @if (empty($rental_status->pivot->return_datetime))
                                             貸出中
                                        @endif
                                     @endforeach
                                </td>
                                <td>
                                    @foreach ($book->rental_statuses as $rental_status)
                                        @if (empty($rental_status->pivot->return_datetime))
                                            {{ date('Y/n/j/(D)', strtotime($rental_status->pivot->rental_start_datetime.("+7 day"))) }}
                                        @endif
                                    @endforeach
                                </td>
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
