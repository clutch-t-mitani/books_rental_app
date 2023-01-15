@extends('layouts.app_book')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color: #000066; color: white; display: flex; justify-content: space-between;">
                    <div style="display: inline-block;">レンタル商品一覧</div>
                    <div style="display: inline-block; "><a href="{{ url('/mypage') }}" class="user-link">現在のレンタル状況</a></div>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
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
                                    @foreach ($book->categories as $category)
                                        【{{ $category->name }}】<br>
                                    @endforeach
                                </td>
                                <td>{{ $book->name }}</td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    {{--
                                        countは配列の個数を調べる
                                        $loop->last 1番
                                     --}}
                                    @if (count($book->rental_statuses))
                                        @foreach ($book->rental_statuses as $rental_status)
                                            @if ($loop->last)
                                                @if (!empty($rental_status->return_datetime))
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rentalButtton{{ $book->id }}">
                                                        借りる
                                                    </button>
                                                @else
                                                    貸出中
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rentalButtton{{ $book->id }}">
                                            借りる
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    @foreach ($book->rental_statuses as $rental_status)
                                        @if (empty($rental_status->return_datetime))
                                            @php
                                                $scheduled_return_day = date('Y/n/j' , strtotime($rental_status->rental_start_datetime.("+7 day")));
                                                $day_no = date('w', strtotime($scheduled_return_day));
                                            @endphp
                                            {{ $scheduled_return_day.'(' .$day_of_week[$day_no].')' }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <div class="modal fade" id="rentalButtton{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('books.store') }}" method="post" name="myform">
                                            @csrf
                                            <input type="hidden" nama="id" value="{{ $book->id }}" >
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">【{{ $book->name }}】レンタルしますか？</h4>
                                            </div>
                                            <div class="modal-body">
                                                <label>返却予定日 ：{{ date('Y年m月d日('.$day_of_week[date('w')].')',strtotime("+7 day")) }}</label>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                                <button type="submit" class="btn btn-primary" onclick="clickEvent()">レンタルする</button>
                                                {{-- <input type="button" onclick="clickEvent()" value="送信" /> --}}
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function clickEvent() {
        console.log(document.myform);
    //  document.myform.submit();

}
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

