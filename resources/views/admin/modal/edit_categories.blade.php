<div class="modal fade" id="edit_categories" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">カテゴリの編集</h4>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                        <form action="{{ route('admin.category.store') }}" method="post">
                        @csrf
                        <tr>
                            <td><input type="text" class="form-control" name="name" placeholder="新規登録するカテゴリ名" required></td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">新規登録</button>
                            </td>
                        </tr>
                        </form>
                        @foreach ($categories as $category)
                        <form action="{{ route('admin.category.update') }}" method="post">
                            @csrf
                            <tr>
                                <td><input type="text" class="form-control" name="name" value="{{ $category->name }}" required></td>
                                <td>
                                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                                    <button type="submit" class="btn btn-primary btn-sm" name="category_action" value="edit">編集</button>
                                    <button type="submit" class="btn btn-danger btn-sm"  name="category_action" value="delete"
                                    onclick="return confirm('削除すると復元できません。\r\n本に紐付けされているカテゴリも削除されます。\r\n')">
                                        削除
                                    </button>

                                </td>
                            </tr>
                        </form>

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
