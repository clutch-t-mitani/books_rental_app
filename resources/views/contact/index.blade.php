@extends('layouts.app_book')

@section('content')
<form method="POST" action="{{ route('contact.confirm') }}">
    @csrf

    <label>メールアドレス</label>
    <input
        name="email"
        value="{{ old('email') }}"
        type="text"><br>
    @if ($errors->has('email'))
        <p class="error-message">{{ $errors->first('email') }}</p>
    @endif

    <label>タイトル</label>
    <input
        name="title"
        value="{{ old('title') }}"
        type="text"><br>
    @if ($errors->has('title'))
        <p class="error-message">{{ $errors->first('title') }}</p>
    @endif

    <label>お問い合わせ内容</label>
    <textarea name="body">{{ old('body') }}</textarea><br>
    @if ($errors->has('body'))
        <p class="error-message">{{ $errors->first('body') }}</p>
    @endif

    <button type="submit">入力内容確認</button>
</form>
@endsection
