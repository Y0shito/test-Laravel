@extends('layouts.template')

@section('title','TOP')

@section('content')
<form action="update_info" method="post">
    @csrf
    @if (isset($info))
    <div class="form-group">
        自己紹介
        <textarea class="form-control" name="introduction" rows="10" placeholder="30文字以上、1000文字以下で入力してください">{{$info->introduction}}</textarea>
    </div>

    <div class="form-group">
        リンク（自身のウェブページなど）
        <input type="text" class="form-control" name="link_name" placeholder="50文字以下で入力してください"
            value="{{$info->link_name}}">
    </div>

    <div class="form-group">
        リンク先URL
        <input type="text" class="form-control" name="url" placeholder="URLを入力してください" value="{{$info->url}}">
    </div>
    @else
    <div class="form-group">
        自己紹介
        <textarea class="form-control" name="introduction" rows="10" placeholder="30文字以上、1000文字以下で入力してください"></textarea>
    </div>

    <div class="form-group">
        リンク（自身のウェブページなど）
        <input type="text" class="form-control" name="link_name" placeholder="50文字以下で入力してください" value="">
    </div>

    <div class="form-group">
        リンク先URL
        <input type="text" class="form-control" name="url" placeholder="URLを入力してください" value="">
    </div>

    @endif

    <div class="d-flex justify-content-center mb-3">
        <div class="button">
            <input class="btn btn-primary btn-lg" type="submit" value="確定">
        </div>
    </div>
</form>
@endsection
