@extends('layouts.template')

@section('title', '検索結果')

@section('content')
    <div class="d-flex">
        <div>
            <h3>
                {{ !empty($category) ? "「{$word}」、カテゴリ「{$category}」の検索結果：件" : "「{$word}」の検索結果：件" }}
            </h3>
            {{-- 検索結果が現在ページの合計になっている --}}
        </div>
        <div class="ml-auto">
            <div class="btn-group">
                @sortablelink('title','タイトル順','', ['class' => 'btn btn-outline-secondary'])
                @sortablelink('view','閲覧数','', ['class' => 'btn btn-outline-secondary'])
                @sortablelink('created_at','作成日順','', ['class' => 'btn btn-outline-secondary'])
                {{-- 閲覧数はデフォルトでdescにしたい --}}
            </div>
        </div>
    </div>

    {{-- ここから検索結果 --}}
    @if (count($items) == 0)
        <div class="alert alert-warning">
            記事が見つかりませんでした
        </div>
    @endif

    @foreach ($items as $item)
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
                                <button class="btn btn-primary btn-sm" type="submit" formaction="bmRemove" name="id"
                                    value="{{ $item->id }}">ブックマーク中</button>
                            @else
                                <button class="btn btn-outline-primary btn-sm" type="submit" formaction="bmAdd" name="id"
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
                    カテゴリ：{{ $item->category }}&nbsp;
                    作成者：{{ $item->author->name }}&nbsp;
                </p>
            </div>
        </div>
    @endforeach
    <div class="d-flex justify-content-center">
        {{ $items->appends(request()->query())->links() }}
    </div>
@endsection
