@extends('layouts.template')

@section('title','確認')

@section('top_title','内容確認')

@section('content')
<div class="alert alert-warning">
    記事はまだ投稿されていません。記事内容をご確認ください
</div>

<form method="post">
    @csrf
    <div class="d-flex">
        <div>
            <h3>{{session()->get('title')}}</h3>
        </div>
        <div class="ml-auto">
            <input class="btn btn-secondary btn-sm" type="submit" value="ブックマーク(--)" disabled>
        </div>
    </div>
    <p>
        閲覧数：--&nbsp;
        作成日：{{Carbon\Carbon::now('Asia/Tokyo')->format('Y年m月d日')}}&nbsp;
        作成者：{{Auth::user()->name}}
    </p>
    <br>
    <p>{!! nl2br(e(session()->get('body'))) !!}</p>

    <select name="category" class="custom-select mb-3">
        <option hidden>カテゴリ絞り込み</option>
        @foreach (config('const.category') as $key => $value)
            <option value="{{$key}}">{{$value}}</option>
        @endforeach
    </select>

    <div class="d-flex justify-content-center mb-3">
        <div class="button">
            <input class="btn btn-secondary btn-lg" type="submit" name="draft" formaction="draft" value="下書きへ保存">
            <input class="btn btn-primary btn-lg" type="submit" name="add" formaction="add" value="投稿">
        </div>
    </div>

</form>
</div>
@endsection
