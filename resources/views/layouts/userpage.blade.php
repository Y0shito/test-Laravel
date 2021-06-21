@extends('layouts.template')

@section('title','ユーザーページ')

@section('content')
<div class="card">
    <div class="card-header d-inline-flex pb-0">
        <h3>{{($user->name)}}のページ</h3>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"></li>
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
            @if(isset($item->updated_at))
            更新日：{{$item->updated_at->format('Y年m月d日') }}&nbsp;
            @else
            作成日：{{$item->created_at->format('Y年m月d日') }}&nbsp;
            @endif
            ブックマーク数：{{$item->bookmarks->count()}}&nbsp;
            カテゴリ：{{$item->category}}
        </p>
    </div>
</div>
@endforeach

@endsection
