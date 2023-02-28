<div class="modal fade" id="delete{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.book.delete') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $book->id }}" >
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">【{{ $book->name }}】を削除しますか？</h4>
                </div>
                <div class="modal-body">
                    <label for="" style="color:red"><i class="fas fa-exclamation-triangle"></i>一度削除すると復元できません。</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    <button type="submit" class="btn btn-danger">削除する</button>
                </div>
            </form>
        </div>
    </div>
</div>
