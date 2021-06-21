@extends('layouts.template')

@section('title','TOP')

@section('content')
<div class="d-flex">
    <div>
        <h3>記事閲覧</h3>
    </div>
    <div class="ml-auto">
        <div class="btn-group">
            <a class="btn btn-outline-secondary" href="/index?sort=id">ID昇順</a>
            <a class="btn btn-outline-secondary" href="/index?sort=title">タイトル順</a>
            <a class="btn btn-outline-secondary" href="/index?sort=view">閲覧数順</a>
            <a class="btn btn-outline-secondary" href="/index?sort=created_at">投稿日順</a>
            {{-- 現時点ですべてasc->ボタンによってdescにもしたい --}}
        </div>
    </div>
</div>

{{-- ここから記事 --}}
@foreach($items as $item)
<div class="card my-3">
    <div class="card-header d-inline-flex pb-0">
        <h3 class="card-title"><a href="article/id/{{$item->id}}">{{$item->title}}</a></h3>
        {{-- ログイン中、かつ自分以外が書いた記事ならtlue --}}
        <div class="ml-auto">
            @if (Auth::check() AND !($item->author_id == Auth::user()->id))
            @if ($item->bookmarks()->where('user_id',Auth::user()->id)->exists())
            {{-- 記事からBookmark引っ張り、その中に自分のidあれば「外す」ボタン --}}
            <form action="bmRemove" method="POST">
                @csrf
                <input class="btn btn-primary btn-sm" type="submit" value="ブックマーク中({{$item->bookmarks->count()}})">
                <input type="hidden" name="id" value="{{$item->id}}">
            </form>
            @else
            <form action="bmAdd" method="POST">
                @csrf
                <input class="btn btn-outline-primary btn-sm" type="submit"
                    value="ブックマークする({{$item->bookmarks->count()}})">
                <input type="hidden" name="id" value="{{$item->id}}">
            </form>
            @endif
            @else
            <input class="btn btn-secondary btn-sm" type="submit" value="ブックマーク({{$item->bookmarks->count()}})"
                disabled>
            @endif
        </div>
    </div>

    <div class="card-body">
        <p class="card-text">{{$item->body}}</p>
    </div>

    <div class="card-footer">
        <p class="card-text">
            閲覧数：{{$item->view}}&nbsp;
            @if(isset($item->updated_at))
            更新日：{{$item->updated_at->format('Y年m月d日') }}&nbsp;
            @else
            作成日：{{$item->created_at->format('Y年m月d日') }}&nbsp;
            @endif
            カテゴリ：{{$item->category}}&nbsp;
            作成者：<a href="user/{{$item->author->id}}">{{$item->author->name}}</a>
        </p>
    </div>
</div>
@endforeach
<div class="d-flex justify-content-center">
    {{$items->appends(['sort' => $sort])->links()}}
</div>
@endsection
