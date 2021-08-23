@extends('layouts.template')

@section('title','')

@section('top_title','')

@section('content')
<div class="d-flex">
    <div>
        <h3>{{$article->title}}</h3>
    </div>
    <div class="ml-auto">
        @if (Auth::check() AND !($article->author_id == Auth::id()))
            <form method="POST">
                @csrf
                @if ($article->bookmarks()->where('user_id',Auth::id())->exists())
                    <button class="btn btn-primary btn-sm" type="submit" formaction="/bmRemove" name="id"
                        value="{{$article->id}}">ブックマーク中</button>
                @else
                    <button class="btn btn-outline-primary btn-sm" type="submit" formaction="/bmAdd" name="id"
                    value="{{$article->id}}">ブックマーク</button>
                @endif
            </form>
            @else
                <button class="btn btn-secondary btn-sm" disabled>ブックマーク</button>
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
    ブックマーク数：{{$article->bookmarks->count()}}&nbsp;
    作成者：<a href="/user/{{$article->author->id}}">{{$article->author->name}}</a>
</p>
<br>
<p>{!! nl2br(e($article->body)) !!}</p>
@endsection
