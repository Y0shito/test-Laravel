@extends('layouts.template')

@section('content')
@if(count($errors) > 0)
<div class="text-danger">
    @foreach($errors->all() as $error)
    <p>{{$error}}</p>
    @endforeach
</div>
@endif

<form action="create" method="post">
    @csrf
    <div class="form-group">
        タイトル
        <input type="text" class="form-control" name="title" placeholder="5文字以上、30文字以下で入力してください" value="{{old('title')}}">
    </div>

    <div class="form-group">
        本文
        <textarea class="form-control" name="body" rows="15"
            placeholder="30文字以上、1000文字以下で入力してください">{{old('body')}}</textarea>
    </div>

    <div class="d-flex justify-content-center mb-3">
        <div class="button">
            <input class="btn btn-secondary btn-lg" type="submit" class="draft" value="下書きへ保存">
            <input type="hidden" name="proc" value="draft">

            <input class="btn btn-primary btn-lg" type="submit" class="add" value="投稿">
            <input type="hidden" name="proc" value="add">
        </div>
    </div>
</form>
@endsection
