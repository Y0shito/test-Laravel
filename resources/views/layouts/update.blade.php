@extends('layouts.template')

@section('title','記事更新')

@section('content')
@if(count($errors) > 0)
<div class="text-danger">
    @foreach($errors->all() as $error)
    <p>{{$error}}</p>
    @endforeach
</div>
@endif

<form method="post">
    @csrf
    <input type="hidden" name="id" value="{{$form->id}}">

    <div class="form-group">
        タイトル
        <input type="text" class="form-control" name="title" placeholder="5文字以上、30文字以下で入力してください"
            value="{{$form->title}}">
    </div>

    <div class="form-group">
        内容
        <textarea class="form-control" name="body" rows="15"
            placeholder="30文字以上、1000文字以下で入力してください">{{$form->body}}</textarea>
    </div>

    <div class="d-flex justify-content-center mb-3">
        <div class="button">
            <input class="btn btn-secondary" type="submit" name="re_draft" formaction="re_draft" value="下書きへ保存">
            <input class="btn btn-primary" type="submit" name="update" formaction="update" value="変更して公開">
        </div>
    </div>
</form>
@endsection
