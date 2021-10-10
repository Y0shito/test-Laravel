<?php

?>
@extends('layouts.template')

@section('title', 'マイページ')

@section('content')
    <div class="card">
        <div class="card-header d-inline-flex pb-0">
            <h3>{{ Auth::user()->name }}のマイページ</h3>
            <div class="ml-auto">
                <div class="ml-auto d-inline-flex">
                    <form name="logout" onsubmit="userLogout(); return false;" action="mypage" method="post">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm">ログアウト</button>
                    </form>
                </div>
            </div>
        </div>
        <ul class="list-group list-group-flush">
            @if (!empty($info))
                {{-- 何も入力されていない場合、NULLは無表示 --}}
                @if (!empty($info->introduction))
                    <li class="list-group-item">{{ $info->introduction }}</li>
                @endif

                @if (!empty($info->link_name))
                    <li class="list-group-item"><a href="{{ $info->url }}">{{ $info->link_name }}</a></li>
                @endif
            @endif

            <li class="list-group-item">
                総記事数：{{ count(Auth::user()->articles) }}&nbsp;
                総閲覧数：{{ Auth::user()->articles()->sum('view') }}&nbsp;
                フォロー数：{{ count($follows) }}&nbsp;
                フォロワー数：{{ count($followers) }}
            </li>
        </ul>
    </div>
    <br>


    {{-- Bootstrap タブパネル --}}
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
            <a class="nav-link" id="pills-followers-tab" data-toggle="pill" href="#pills-followers" role="tab"
                aria-controls="pills-followers" aria-selected="false">フォロワー</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-config-tab" data-toggle="pill" href="#pills-config" role="tab"
                aria-controls="pills-config" aria-selected="false">設定</a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        {{-- ここから執筆記事 --}}
        <div class="tab-pane fade show active" id="pills-articles" role="tabpanel">
            <h3>公開中の記事：{{ count($items) }}件</h3>

            @foreach ($items as $item)
                <div class="card my-3">
                    <div class="card-header d-inline-flex pb-0">
                        <h3><a href="article/id/{{ $item->id }}">{{ $item->title }}</a></h3>
                        <div class="ml-auto d-inline-flex">
                            <form name="articleOptions" method="POST">
                                @csrf

                                @if ($item->open == \App\Enums\PublicStatus::OPEN)
                                    <button class="btn btn-secondary btn-sm" type="submit" formaction="close" name="id"
                                        value="{{ $item->id }}">非公開にする</button>
                                @else
                                    <button class="btn btn-success btn-sm" type="submit" formaction="open" name="id"
                                        value="{{ $item->id }}">公開する</button>
                                @endif

                                <button class="btn btn-primary btn-sm" type="submit" formaction="edit" name="id"
                                    value="{{ $item->id }}">編集</button>
                            </form>
                            <form name="articleDelete" action="delete" method="POST"
                                onsubmit="articleDelete(); return false;">
                                @csrf
                                <button class="btn btn-danger btn-sm" type="submit" name="id" value="{{ $item->id }}">削除</button>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <p class="card-text">{{ mb_strimwidth($item->body, 0, 450, '...') }}</p>
                    </div>

                    <div class="card-footer">
                        <p class="card-text">
                            閲覧数：{{ $item->view }}&nbsp;

                            {{ isset($item->updated_at) ? "更新日：{$item->updated_at->format('Y年m月d日')}" : "作成日：{$item->created_at->format('Y年m月d日')}" }}

                            ブックマーク数：{{ $item->bookmarks->count() }}&nbsp;
                            カテゴリ：{{ $item->category }}
                        </p>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        </div>

        {{-- ここからブックマークした記事 --}}
        <div class="tab-pane fade" id="pills-bookmarks" role="tabpanel">
            <h3>ブックマークした記事：{{ count($bookmarks) }}件</h3>

            @foreach ($bookmarks as $bookmark)
                <div class="card my-3">
                    <div class="card-header d-inline-flex pb-0">
                        <h3><a href="article/id/{{ $bookmark->articles->id }}">{{ $bookmark->articles->title }}</a>
                        </h3>
                    </div>

                    <div class="card-body">
                        <p class="card-text">{{ $bookmark->articles->body }}</p>
                    </div>

                    <div class="card-footer">
                        <p class="card-text">
                            閲覧数：{{ $bookmark->articles->view }}&nbsp;

                            {{ isset($bookmark->articles->updated_at) ? "作成日：{$bookmark->articles->updated_at->format('Y年m月d日')}" : "更新日：{$bookmark->articles->created_at->format('Y年m月d日')}" }}

                            作成者：{{ $bookmark->articles->author->name }}&nbsp;
                            カテゴリ：{{ $bookmark->articles->category }}
                        </p>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-center">
                {{ $bookmarks->links() }}
            </div>
        </div>

        {{-- ここからフォローした人 --}}
        <div class="tab-pane fade" id="pills-follows" role="tabpanel">
            <h3>フォローしている人：{{ count($follows) }}件</h3>
            <table class="table table-striped table-borderless">
                <tbody>
                    <form action="/unfollow" method="POST">
                        @csrf
                        @foreach ($follows as $item)
                            <tr>
                                <td><a href="user/{{ $item->id }}">{{ $item->name }}</a></td>
                                <td><button class="btn btn-danger btn-sm" type="submit" name="id"
                                        value="{{ $item->id }}">フォローを外す</button></td>
                            </tr>
                        @endforeach
                    </form>
                </tbody>
            </table>
        </div>

        {{-- ここからフォローしてくれている人 --}}
        <div class="tab-pane fade" id="pills-followers" role="tabpanel">
            <h3>フォロワー数：{{ count($followers) }}件</h3>
            <table class="table table-striped table-borderless">
                <tbody>
                    {{-- <form method="POST">
                    @csrf
                    @foreach ($followers as $item)
                    <tr>
                        <td><a href="user/{{$item->id}}">{{$item->name}}</a></td>
                <td>
                    @if (Auth::user()->getFollowers()->where('user_id', Auth::id())->exists())
                    <button class="btn btn-danger btn-sm" type="submit" formaction="/unfollow" name="id" value="{{$item->id}}">フォローを外す</button>
                    @else
                    <button class="btn btn-outline-primary btn-sm" type="submit" formaction="/follow" name="id" value="{{$item->id}}">フォロー</button>
                    @endif
                </td>
                </tr>
                @endforeach
                </form> --}}
                </tbody>
            </table>
        </div>

        {{-- ここからプロフィール設定など --}}
        <div class="tab-pane fade" id="pills-config" role="tabpanel">
            <form action="update_info" method="post">
                @csrf
                <div class="form-group">
                    自己紹介
                    <textarea class="form-control" name="introduction" rows="10"
                        placeholder="30文字以上、1000文字以下で入力してください">{{ isset($info) ? $info->introduction : '' }}</textarea>
                </div>

                <div class="form-group">
                    リンク（自身のウェブページなど）
                    <input type="text" class="form-control" name="link_name" placeholder="50文字以下で入力してください"
                        value="{{ isset($info) ? $info->link_name : '' }}">
                </div>

                <div class="form-group">
                    リンク先URL
                    <input type="text" class="form-control" name="url" placeholder="URLを入力してください"
                        value="{{ isset($info) ? $info->url : '' }}">
                </div>

                <div class="d-flex justify-content-center mb-3">
                    <div class="button">
                        <input class="btn btn-primary btn-lg" type="submit" value="確定">
                    </div>
                </div>
            </form>
            <div class="alert alert-danger">
                <form name="userDelete" action="userDelete" onsubmit="unsubscribe(); return false;" method="GET">
                    <button class="btn btn-danger btn-sm">退会する</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/mypage.js') }}"></script>
@endsection
