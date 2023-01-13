@extends('layouts.app_book')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color: #000066; color: white; display: flex; justify-content: space-between;">
                    <div style="display: inline-block;">レンタル商品一覧</div>
                    <div style="display: inline-block; "><a href="{{ url('/user') }}" class="user-link">現在のレンタル状況</a></div>
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
                                    <button type="button" data-sample="button2" class="btn btn-primary" data-toggle="modal" data-target="#rentalButtton{{ $book->id }}">借りる</button><br>

                                    @foreach ($book->rental_statuses as $rental_status)
                                        @if (empty($rental_status->pivot->return_datetime))
                                             貸出中
                                        @endif
                                     @endforeach
                                </td>
                                <td>
                                    @foreach ($book->rental_statuses as $rental_status)
                                        @if (empty($rental_status->pivot->return_datetime))
                                            {{ date('Y/n/j/('.$day_of_week[date('w')].')' ,strtotime($rental_status->pivot->rental_start_datetime.("+7 day"))) }}
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <div class="modal fade" id="rentalButtton{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">【{{ $book->name }}】レンタルしますか？</h4>
                                        </div>
                                        <div class="modal-body">
                                            <label>返却予定日 ：{{ date('Y年m月d日('.$day_of_week[date('w')].')',strtotime("+7 day")) }}</label>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                            <button type="button" class="btn btn-danger">削除</button>
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

