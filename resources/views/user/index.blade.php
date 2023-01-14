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
                                @foreach ($user->rental_statuses as $rental_book)
                                    @if (empty($rental_book->return_datetime))
                                    <tr>
                                        <td>{{ $rental_book->book->name }}</td>
                                        @php
                                            $rentarl_day = date('Y/n/j' , strtotime($rental_book->rental_start_datetime));
                                            $rentarl_day_no = date('w', strtotime($rentarl_day));
                                            $scheduled_return_day = date('Y/n/j' , strtotime(($rental_book->rental_start_datetime.("+7 day"))));
                                            $scheduled_return_day_no = date('w', strtotime($rentarl_day));
                                        @endphp
                                        <td>{{ $rentarl_day.'(' .$day_of_week[$rentarl_day_no].')' }}</td>
                                        <td>{{ $scheduled_return_day.'(' .$day_of_week[$scheduled_return_day_no].')' }}</td>
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
                                @foreach ($user->rental_statuses as $rentaled_book)
                                    @if (!empty($rentaled_book->return_datetime))
                                    <tr>
                                        <td>{{ $rentaled_book->book->name }}</td>
                                        @php
                                            $rentarl_day = date('Y/n/j' , strtotime($rentaled_book->rental_start_datetime));
                                            $rentarl_day_no = date('w', strtotime($rentarl_day));
                                            $return_day = date('Y/n/j' , strtotime(($rentaled_book->return_datetime)));
                                            $return_day_no = date('w', strtotime($return_day));
                                        @endphp
                                        <td>{{ $rentarl_day.'(' .$day_of_week[$rentarl_day_no].')' }}</td>
                                        <td>{{ $return_day.'(' .$day_of_week[$return_day_no].')' }}</td>
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

