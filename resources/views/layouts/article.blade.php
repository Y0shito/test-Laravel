@extends('layouts.template')

@section('title','')

@section('top_title','')

@section('content')
<div class="d-flex">
    <div>
        <h3>{{$article->title}}</h3>
    </div>
    <div class="ml-auto">
        @if (Auth::check() AND !($article->author_id == Auth::user()->id))
        @if ($article->bookmarks()->where('user_id',Auth::user()->id)->exists())
        {{-- 記事からBookmark引っ張り、その中に自分のidあれば「外す」ボタン --}}
        <form action="bmRemove" method="POST">
            @csrf
            <input class="btn btn-primary btn-sm" type="submit" value="ブックマーク中({{$article->bookmarks->count()}})">
            <input type="hidden" name="id" value="{{$article->id}}">
        </form>
        @else
        <form action="bmAdd" method="POST">
            @csrf
            <input class="btn btn-outline-primary btn-sm" type="submit"
                value="ブックマークする({{$article->bookmarks->count()}})">
            <input type="hidden" name="id" value="{{$article->id}}">
        </form>
        @endif
        @else
        <input class="btn btn-secondary btn-sm" type="submit" value="ブックマーク({{$article->bookmarks->count()}})"
            disabled>
        @endif
    </div>
</div>
<p>
    閲覧数：{{$article->view}}&nbsp;
    @if(isset($article->updated_at))
    更新日：{{$article->updated_at->format('Y年m月d日')}}&nbsp;
    @endif
    作成日：{{$article->created_at->format('Y年m月d日')}}&nbsp;
    カテゴリ：{{$article->category}}&nbsp;
    作成者：{{$article->author->name}}
</p>
<br>
<p>{!! nl2br(e($article->body)) !!}</p>
@endsection
