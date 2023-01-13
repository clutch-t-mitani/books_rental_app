@extends('layouts.app_book')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color: #000066; color: white">
                    レンタル状況
                </div>
                <div>

                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div style="margin-bottom: 15px; text-align: right;">
                            <span style="text-decoration: underline;">お名前:&emsp;{{ $user->name }} 様</span><br>
                            <span style="text-decoration: underline;">メールアドレス:&emsp;{{ $user->email }} </span>
                        </div>
                        <h5 style="margin-bottom: 20px;text-decoration: underline;">レンタル中商品</h5>
                        <table class="table table-hover">
                            <thead>
                                <tr style="background-color: #FFFFDD;">
                                    <th>タイトル</th>
                                    <th>レンタル日</th>
                                    <th>返却予定日</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->rental_statuses as $book)
                                    @if (empty($book->pivot->return_datetime))
                                    <tr>
                                        <td>{{ $book->name }}</td>
                                        <td>{{ date('Y/n/j/('.$day_of_week[date('w')].')',strtotime($book->pivot->rental_start_datetime)) }}</td>
                                        <td>{{ date('Y/n/j/('.$day_of_week[date('w')].')', strtotime($book->pivot->rental_start_datetime.("+7 day"))) }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <h5 style="margin-bottom: 20px; text-decoration: underline;">レンタル履歴</h5>
                        <table class="table table-hover">
                            <thead>
                                <tr style="background-color: #FFFFDD">
                                    <th>タイトル</th>
                                    <th>レンタル日</th>
                                    <th>返却日</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->rental_statuses as $book)
                                    @if (!empty($book->pivot->return_datetime))
                                    <tr>
                                        <td>{{ $book->name }}</td>
                                        <td>{{ date('Y/n/j/('.$day_of_week[date('w')].')',strtotime($book->pivot->rental_start_datetime)) }}</td>
                                        <td>{{ date('Y/n/j/('.$day_of_week[date('w')].')', strtotime($book->pivot->rental_start_datetime.("+7 day"))) }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
