<div class="modal fade" id="ranking" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    {{-- <div class="modal-dialog"> --}}
    <div class="modal-dialog" style="max-width: inherit; width: 60%; ">
        <div class="modal-content">
            <div class="card-body">
                <table class="table table-striped  table-bordered" style="font-size: 80%">
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
                    @foreach ($ranking_books as $rental_book)
                        @php
                            if (!$rental_book->rental_count) {
                                break;
                            }
                            if($bef_point != $rental_book->rental_count){
                                $rank = $cnt;
                            }
                            if($rank == 11){
                                break;
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
