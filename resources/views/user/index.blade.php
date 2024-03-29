@extends('layouts.app_book')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color: #000066; color: white; display: flex; justify-content: space-between;">
                    <div style="display: inline-block;"><i class="fas fa-user"></i>マイページ</div>
                </div>
                <div class="card-body">
                    <div class="card" style="text-align: center;">
                        <ul class="list-group list-group-flush">
                            @if ($over_date_books->isNotEmpty())
                                <li class="list-group-item" style="color:red">
                                    <i class="fas fa-exclamation-triangle"></i>返却期日超えの本が{{ count($over_date_books) }}冊あります<i class="fas fa-exclamation-triangle"></i>
                                </li>
                            @endif
                            <li class="list-group-item">お名前：{{ $user->name }}様</li>
                            <li class="list-group-item">レンタル中：{{ count($rental_statues) }}冊</li>
                            <li class="list-group-item">
                                <button type="button" class="btn btn-outline-secondary" style="width:80%" onclick="location.href = '{{ url('/') }}'">商品一覧へ戻る</button>
                            </li>
                        </ul>
                    </div>
                    <br>
                    <div class="card" style="text-align: center;">
                        <div style="margin-bottom: 5px">レンタル中商品</div>
                        <table class="table table-bordered">
                            <thead >
                                <tr>
                                    <th style="width: %">カテゴリー</th>
                                    <th style="width: %">タイトル</th>
                                    <th style="width: %">作者</th>
                                    <th style="width: %">返却期日</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rental_statues as $status)
                                    <tr style={{ now() > $status->rental_start_datetime->addDays(8)? "color:red" : "" }}>
                                        <td>
                                            @foreach ($status->book->book_categories as $book_category)
                                                【{{ $book_category->category->name }} 】<br>
                                            @endforeach
                                        </td>
                                        <td>{{ $status->book->name }}</td>
                                        <td>{{ $status->book->author }}</td>
                                        <td>{{ $status->rental_start_datetime->addDays(7)->isoFormat('YYYY/MM/DD(ddd)') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

