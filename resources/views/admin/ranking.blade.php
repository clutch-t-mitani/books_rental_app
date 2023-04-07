@extends('layouts.app_admin')

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
                        @foreach ($rental_count_list as $rental_count_book)
                            <tr>
                                <td></td>
                                <td></td>
                                {{-- <td>
                                    @foreach ($rental_count_book->book->book_categories as $book_category)
                                        【{{ $book_category->category->name }}】<br>
                                    @endforeach
                                </td> --}}
                                {{-- <td>{{ $rentaled_book_status->book->name }}</td>
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
                                </td> --}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{-- {{ $rental_count_list->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

