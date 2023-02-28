<div class="modal fade" id="returnButtton{{ $rentaled_book_status->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.update') }}" method="post">
                @csrf
                <input type="hidden" name="status_id" value="{{ $rentaled_book_status->id }}" >
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">【{{ $rentaled_book_status->book->name }}】返却登録しますか？</h4>
                </div>
                <div class="modal-body">
                    <label>レンタル者：  {{ $rentaled_book_status->user->name }} 様</label><br>
                    <label>返却期日：  {{ $rentaled_book_status->rental_start_datetime->addDays(7)->isoFormat('YYYY年MM月DD(ddd)') }}</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    <button type="submit" class="btn btn-primary">返却登録する</button>
            </form>
        </div>
    </div>
</div>
