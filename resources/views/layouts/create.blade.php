@extends('layouts.template')

@section('content')

@if(count($errors) > 0)
<div class="alert alert-danger">
    @foreach($errors->all() as $error)
    {{$error}}<br>
    @endforeach
</div>
@endif

<form action="create" method="post">
    @csrf
    <div class="form-group">
        タイトル
        @if (session()->has('title'))
        <input type="text" class="form-control" name="title" placeholder="5文字以上、30文字以下で入力してください" value="{{session()->get('title')}}">
        @else
        <input type="text" class="form-control" name="title" placeholder="5文字以上、30文字以下で入力してください" value="{{old('title')}}">
        @endif
    </div>

    <div class="form-group">
        本文
        @if (session()->has('body'))
        <textarea class="form-control" name="body" rows="15"
            placeholder="30文字以上、1000文字以下で入力してください">{{session()->get('body')}}</textarea>
        @else
        <textarea class="form-control" name="body" rows="15"
            placeholder="30文字以上、1000文字以下で入力してください">{{old('body')}}</textarea>
        @endif
    </div>

    <div class="d-flex justify-content-center mb-3">
        <div class="button">
            <input class="btn btn-primary btn-lg" type="submit" value="プレビュー">
        </div>
    </div>

</form>
@endsection
