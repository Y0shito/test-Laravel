@extends('layouts.template')

@section('title','ユーザーページ')

@section('content')
<div class="card">
    <div class="card-header d-inline-flex pb-0">
        <h3 class="card-title">{{($user->name)}}のページ</h3>
        <div class="ml-auto">
            {{-- ログインかつ自分自身でないか --}}
            @if (Auth::check() AND !($user->id == Auth::id()))
            {{-- 相手ユーザーIDと、自身のfollowテーブル内に相手のユーザーIDが一致するか --}}
            @if ($user->getFollowers()->where('user_id', Auth::id())->exists())
            <form action="/unfollow" method="POST">
                @csrf
                <input class="btn btn-danger btn-sm" type="submit" value="フォローを外す">
                <input type="hidden" name="id" value="{{$user->id}}">
            </form>
            @else
            <form action="/follow" method="POST">
                @csrf
                <input class="btn btn-outline-primary btn-sm" type="submit" value="フォローする">
                <input type="hidden" name="id" value="{{$user->id}}">
            </form>
            @endif
            @else
            <input class="btn btn-secondary btn-sm" type="submit" value="フォローする" disabled>
            @endif
        </div>
    </div>
    <ul class="list-group list-group-flush">
        @if (!empty($info))
            {{-- 何も入力されていない場合、NULLは無表示 --}}
            @if (!empty($info->introduction))
            <li class="list-group-item">{{$info->introduction}}</li>
            @endif

            @if (!empty($info->link_name))
            <li class="list-group-item"><a href="{{$info->url}}">{{$info->link_name}}</a></li>
            @endif
        @endif

        <li class="list-group-item">
            総記事数：{{count($articles)}}&nbsp;
            総閲覧数：{{$user->articles()->sum('view')}}
        </li>
    </ul>
</div>
<br>

{{-- ここから執筆記事 --}}
@foreach($articles as $item)
<div class="card my-3">
    <div class="card-header d-inline-flex pb-0">
        <h3><a href="/article/id/{{$item->id}}">{{$item->title}}</a></h3>
    </div>

    <div class="card-body">
        <p class="card-text">{{$item->body}}</p>
    </div>

    <div class="card-footer">
        <p class="card-text">
            閲覧数：{{$item->view}}&nbsp;

            {{ isset($item->updated_at)
            ? "更新日：{$item->updated_at->format('Y年m月d日')}"
            : "作成日：{$item->created_at->format('Y年m月d日')}"
            }}

            ブックマーク数：{{$item->bookmarks->count()}}&nbsp;
            カテゴリ：{{$item->category}}
        </p>
    </div>
</div>
@endforeach

@endsection
