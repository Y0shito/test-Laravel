@extends('layouts.template')

@section('title','編集ページ')

@section('content')

@if(count($errors) > 0)
<div class="alert alert-danger">
    @foreach($errors->all() as $error)
    {{$error}}<br>
    @endforeach
</div>
@endif

<form method="POST">
    @csrf
    <div class="form-group">
        タイトル
        <input type="text" class="form-control" name="title" placeholder="5文字以上、30文字以下で入力してください"
            value="{{ $errors->has('*') ? old('title') : ($article['title'] ?? '' )}}">
    </div>

    <div class="form-group">
        本文
        <textarea class="form-control" name="body" rows="15"
            placeholder="30文字以上、1000文字以下で入力してください">{{ $errors->has('*') ? old('body') : ($article['body'] ?? '' )}}</textarea>
    </div>

    <input type="hidden" name="article_id" value="{{ $article['id'] ?? '' }}">

    <div class="d-flex justify-content-center mb-3">
        <div class="button">
            <input class="btn btn-secondary btn-lg" type="submit" formaction="draft" value="下書きへ保存">
            <input class="btn btn-primary btn-lg" type="submit" formaction="create" onsubmit="to_preview(this)" value="プレビュー">
        </div>
    </div>

</form>
<script src="{{asset('/js/create.js')}}"></script>
@endsection
