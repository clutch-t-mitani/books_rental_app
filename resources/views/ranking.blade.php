@extends('layouts.app_book')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color:red; color: white; display: flex; justify-content: space-between;">
                    <div style="display: inline-block;">レンタル済み商品一覧</div>
                    @if( Auth::check() )
                        <div style="display: inline-block; ">
                            <a href="{{ route('admin.book') }}" class="link"><i class="fa fa-book"></i>在庫編集ページへ</a><br>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <!--検索フォーム-->
                    <table id="table1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10%">順位</th>
                                <th style="width: 25%">カテゴリー</th>
                                <th style="width: 35%">タイトル</th>
                                <th style="width: 15%">作者</th>
                                <th style="width: 15%">レンタル回数</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rank = 1;
                                $cnt = 1;
                                $bef_point = 0;
                            @endphp
                        @foreach ($sorted_rental_books as $rental_book)
                            @php
                                if (!$rental_book->rental_count) {
                                    break;
                                }
                                if($bef_point != $rental_book->rental_count){
                                    $rank = $cnt;
                                }
                            @endphp
                            <tr>
                                <td>{{ $rank }}位</td>
                                <td>
                                    @foreach ($rental_book->book_categories as $book_category)
                                        【{{ $book_category->category->name }}】<br>
                                    @endforeach
                                </td>
                                <td>{{ $rental_book->name }}</td>
                                <td>{{ $rental_book->author }}</td>
                                <td>{{ $rental_book->rental_count }}回</td>
                                @php
                                    $bef_point = $rental_book->rental_count;
                                    $cnt++;
                                @endphp
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{-- {{ $books->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

