<div class="modal fade" id="rentalButtton{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('cart.add') }}" method="post">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}" >
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">【{{ $book->name }}】レンタルしますか？</h4>
                </div>
                <div class="modal-body">
                    <label>返却期日 ：{{ now()->addDays(7)->isoFormat('YYYY年MM月DD(ddd)') }}</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    <button type="submit" class="btn btn-primary">カートに入れる</button>
            </form>
            </div>
        </div>
    </div>
</div>
