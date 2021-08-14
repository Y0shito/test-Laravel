<?php

?>
@extends('layouts.template')

@section('title','マイページ')

@section('content')
<div class="card">
    <div class="card-header d-inline-flex pb-0">
        <h3>{{Auth::user()->name}}のマイページ</h3>
        <div class="ml-auto">
            <div class="ml-auto d-inline-flex">
                <form action="mypage" method="post">
                    @csrf
                    <input class="btn btn-outline-danger btn-sm" type="submit" value="ログアウト">
                </form>
            </div>
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
            総記事数：{{count(Auth::user()->articles)}}&nbsp;
            総閲覧数：{{Auth::user()->articles()->sum('view')}}&nbsp;
            フォロワー数：{{$getFollowers}}
        </li>
    </ul>
</div>
<br>



<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="pills-articles-tab" data-toggle="pill" href="#pills-articles" role="tab"
            aria-controls="pills-articles" aria-selected="true">記事</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pills-bookmarks-tab" data-toggle="pill" href="#pills-bookmarks" role="tab"
            aria-controls="pills-bookmarks" aria-selected="false">ブックマーク</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pills-follows-tab" data-toggle="pill" href="#pills-follows" role="tab"
            aria-controls="pills-follows" aria-selected="false">フォロー</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pills-follows-tab" data-toggle="pill" href="#pills-config" role="tab"
            aria-controls="pills-config" aria-selected="false">設定</a>
    </li>
</ul>

<div class="tab-content" id="pills-tabContent">
    {{-- ここから執筆記事 --}}
    <div class="tab-pane fade show active" id="pills-articles" role="tabpanel">
        <h3>公開中の記事：{{count($items)}}件</h3>

        @foreach($items as $item)
        <div class="card my-3">
            <div class="card-header d-inline-flex pb-0">
                <h3><a href="article/id/{{$item->id}}">{{$item->title}}</a></h3>
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
                    ブックマーク数：{{$item->bookmarks->count()}}&nbsp;
                    カテゴリ：{{$item->category}}
                </p>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-center">
            {{$items->links()}}
        </div>

    </div>

    {{-- ここからブックマークした記事 --}}
    <div class="tab-pane fade" id="pills-bookmarks" role="tabpanel">
        <h3>ブックマークした記事：{{count($bookmarks)}}件</h3>

        @foreach ($bookmarks as $bookmark)
        <div class="card my-3">
            <div class="card-header d-inline-flex pb-0">
                <h3><a href="article/id/{{$bookmark->articles->id}}">{{$bookmark->articles->title}}</a></h3>
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
                    カテゴリ：{{$bookmark->articles->category}}
                </p>
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-center">
            {{$bookmarks->links()}}
        </div>
    </div>

    {{-- ここからフォローした人 --}}
    <div class="tab-pane fade" id="pills-follows" role="tabpanel">
        <h3>フォローしている人：{{count($getFollows)}}件</h3>
        <ul class="list-gloup">
            @foreach ($getFollows as $item)
            <li class="list-group-item">{{$item->name}}</li>
            @endforeach
        </ul>
    </div>

    {{-- ここからプロフィール設定 --}}
    <div class="tab-pane fade" id="pills-config" role="tabpanel">
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
    </div>
</div>

@endsection
