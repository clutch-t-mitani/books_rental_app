<div class="modal fade" id="create_book" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.book.create') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">本の新規登録</h4>
                </div>
                <div class="modal-body">
                    <label>タイトル</label>
                    <input type="text" class="form-control" name="name" value="" placeholder="タイトルを入力してください" required>
                    <br>
                    <label>作者</label>
                    <input type="text" class="form-control" name="author" value="" placeholder="作者を入力してください" required>
                    <br>
                    <label>カテゴリ</label>
                    <select name="categories_id[]" class="form-control" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    <button type="submit" class="btn btn-primary">登録する</button>
                </div>
            </form>
        </div>
    </div>
</div>
