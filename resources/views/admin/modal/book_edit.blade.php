<div class="modal fade" id="edit{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.book.update') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $book->id }}" >
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">本の内容を編集</h4>
                </div>
                <div class="modal-body">
                    <label>タイトル</label>
                    <input type="text" class="form-control" name="name" value="{{ $book->name }}" placeholder="タイトルを入力してください" required>
                    <br>
                    <label>作者</label>
                    <input type="text" class="form-control" name="author" value="{{ $book->author }}" placeholder="作者を入力してください" required>
                    <br>
                    <label>カテゴリ</label>
                    @php
                        $subject_book_categories = [];
                        foreach ($book_categories as $book_category) {
                            if ($book_category->book_id == $book->id) {
                                array_push($subject_book_categories, $book_category->category_id);
                            }
                        }
                    @endphp
                    <select name="categories_id[]" class="form-control" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if(in_array($category->id,$subject_book_categories))selected @endif>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                    <button type="submit" class="btn btn-primary">編集する</button>
                </div>
            </form>
        </div>
    </div>
</div>
