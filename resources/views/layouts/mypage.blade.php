@extends('layouts.template')

@section('title','マイページ')

@section('content')
<div class="card">
    <div class="card-header d-inline-flex pb-0">
        <h3>{{Auth::user()->name}}のマイページ</h3>
        <div class="ml-auto">
            <form action="mypage" method="post">
                @csrf
                <input class="btn btn-outline-danger btn-sm" type="submit" value="ログアウト">
            </form>
        </div>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"></li>
        <li class="list-group-item">
            総記事数：{{count(Auth::user()->articles)}}&nbsp;
            総閲覧数：{{Auth::user()->articles()->sum('view')}}
        </li>
    </ul>
</div>
<br>

{{-- ここから執筆記事 --}}
<h3>公開中の記事：{{count($items)}}件</h3>

@foreach($items as $item)
<div class="card my-3">
    <div class="card-header d-inline-flex pb-0">
        <h3><a href="article/id={{$item->id}}">{{$item->title}}</a></h3>
        <div class="ml-auto d-inline-flex">
            <form action="edit" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$item->id}}">
                <input class="btn btn-primary btn-sm" type="submit" value="編集">
            </form>

            @if($item->open == 1)
            <form action="close" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$item->id}}">
                <input class="btn btn-secondary btn-sm" type="submit" value="非公開にする">
            </form>
            @else
            <form action="open" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$item->id}}">
                <input class="btn btn-success btn-sm" type="submit" value="公開する">
            </form>
            @endif

            <form action="delete" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{$item->id}}">
                <input class="btn btn-danger btn-sm" type="submit" value="削除">
            </form>
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
            ブックマーク数：{{$item->bookmarks->count()}}
        </p>
    </div>
</div>
@endforeach

{{-- ここからブックマークした記事 --}}
<h3>ブックマークした記事：{{count($bookmarks)}}件</h3>

@foreach ($bookmarks as $bookmark)
<div class="card my-3">
    <div class="card-header d-inline-flex pb-0">
        <h3><a href="article/id={{$bookmark->articles->id}}">{{$bookmark->articles->title}}</a></h3>
    </div>

    <div class="card-body">
        <p class="card-text">{{$bookmark->articles->body}}</p>
    </div>

    <div class="card-footer">
        <p class="card-text">
            閲覧数：{{$bookmark->articles->view}}&nbsp;
            @if(isset($bookmark->articles->updated_at))
            更新日：{{$bookmark->articles->updated_at->format('Y年m月d日') }}&nbsp;
            @else
            作成日：{{$bookmark->articles->created_at->format('Y年m月d日') }}&nbsp;
            @endif
            作成者：{{$bookmark->articles->author->name}}&nbsp;
        </p>
    </div>
</div>
@endforeach

@endsection
