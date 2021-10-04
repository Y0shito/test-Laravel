@extends('layouts.template')

@section('title', 'ユーザーページ')

@section('content')
    <div class="card">
        <div class="card-header d-inline-flex pb-0">
            <h3 class="card-title">{{ $user->name }}のページ</h3>
            <div class="ml-auto">
                {{-- ログインかつ自分自身でないか --}}
                @if (Auth::check() and !($user->id == Auth::id()))
                    <form method="POST">
                        @csrf
                        {{-- 相手ユーザーIDと、自身のfollowテーブル内に相手のユーザーIDが一致するか --}}
                        @if ($user->getFollowers()->where('user_id', Auth::id())->exists())
                            <button class="btn btn-danger btn-sm" type="submit" formaction="/unfollow" name="id"
                                value="{{ $user->id }}">フォローを外す</button>
                        @else
                            <button class="btn btn-outline-primary btn-sm" type="submit" formaction="/follow" name="id"
                                value="{{ $user->id }}">フォロー</button>
                        @endif
                    </form>
                @else
                    <button class="btn btn-secondary btn-sm" type="submit" disabled>フォロー</button>
                @endif
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
                総記事数：{{ count($articles) }}&nbsp;
                総閲覧数：{{ $user->articles()->sum('view') }}
            </li>
        </ul>
    </div>
    <br>

    {{-- ここから執筆記事 --}}
    @foreach ($articles as $item)
        <div class="card my-3">
            <div class="card-header d-inline-flex pb-0">
                <h3 class="card-title"><a href="article/id/{{ $item->id }}">{{ $item->title }}</a></h3>
                <div class="ml-auto">
                    {{-- ログイン中、かつ自分以外が書いた記事ならtlue --}}
                    @if (Auth::check() and !($item->author_id == Auth::id()))
                        {{-- 記事からBookmark引っ張り、その中に自分のidあればfslse --}}
                        <form method="POST">
                            @csrf
                            @if ($item->bookmarks()->where('user_id', Auth::id())->exists())
                                <button class="btn btn-primary btn-sm" type="submit" formaction="/bmRemove" name="id"
                                    value="{{ $item->id }}">ブックマーク中</button>
                            @else
                                <button class="btn btn-outline-primary btn-sm" type="submit" formaction="/bmAdd" name="id"
                                    value="{{ $item->id }}">ブックマーク</button>
                            @endif
                        </form>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>ブックマーク</button>
                    @endif
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

@endsection
