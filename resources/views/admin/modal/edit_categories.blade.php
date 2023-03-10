<div class="modal fade" id="edit_categories" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">カテゴリの編集</h4>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                        <tr>
                            <td><input type="text" class="form-control" name="register_name" value="" placeholder="新規登録するカテゴリ名" ></td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm" name="category_action" value="register">新規登録</button>
                            </td>
                        </tr>
                        @foreach ($categories as $category)
                        <form action="{{ route('admin.category.update') }}" method="post">
                            @csrf
                            <tr>
                                <td><input type="text" class="form-control" name="name" value="{{ $category->name }}" required></td>
                                <td>
                                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                                    <button type="submit" class="btn btn-primary btn-sm" name="category_action" value="edit">編集</button>
                                    {{-- <button type="submit" class="btn btn-danger btn-sm" name="category_action" value="delete">削除</button> --}}
                                    <button type="button" class="btn btn-danger btn-sm"  data-toggle="modal" data-target="#delete_category">削除</button>
                                    {{-- カテゴリの削除 --}}
                                    @include('admin.modal.delete_category')
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
