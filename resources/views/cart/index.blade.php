@extends('layouts.app_book')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-17">
            <div class="card">
                <div class="card-header" style="background-color: #000066; color: white; display: flex; justify-content: space-between;">
                    <div style="display: inline-block;"><i class="fas fa-shopping-cart"></i>カート</div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: %">No</th>
                                <th style="width: %">カテゴリー</th>
                                <th style="width: %">タイトル</th>
                                <th style="width: %">作者</th>
                                <th style="width: %"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($in_cart_books as $key => $book)
                                <form action="{{ route('cart.delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        @foreach ($book->book_categories as $category)
                                            {{ $category->category->name }}<br>
                                        @endforeach
                                        </td>
                                    <td>{{ $book->name }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>
                                        <button type="submit" class="btn btn-link" onClick="delete_alert(event);return false;"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card" style="text-align: center;">
                        <form action="{{ route('cart.store') }}" method="POST">
                        @csrf
                            @foreach ($in_cart_books as $book)
                                <input type="hidden" name="book_id[]" value="{{ $book->id }}" >
                            @endforeach
                            <ul class="list-group list-group-flush">
                                @if (!empty($in_cart_books) )
                                    <li class="list-group-item">返却期日： {{ now()->addDays(7)->isoFormat('YYYY年MM月DD(ddd)') }}</li>
                                    <li class="list-group-item">レンタル合計冊数： {{ count($in_cart_books) }}冊</li>
                                    <li class="list-group-item"><button class="btn btn-primary" style="width:80%" onClick="rental_alert(event);return false;">レンタルする</button></li>
                                @else
                                    <li class="list-group-item">カートに商品は0点です</li>
                                @endif
                                <li class="list-group-item">
                                    <button type="button" class="btn btn-outline-secondary" style="width:80%" onclick="location.href = '{{ url('/') }}'">商品一覧へ戻る</button>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    function delete_alert(e){
        if(!window.confirm('カートから削除しますか？')){
            return false;
        }
        document.deleteform.submit();
    };

    function rental_alert(e){
        if(!window.confirm('カート内商品をレンタルしますか？')){
            return false;
        }
        document.deleteform.submit();
    };
</script>

