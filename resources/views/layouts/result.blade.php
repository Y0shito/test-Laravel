@extends('layouts.template')

@section('title','検索結果')

@section('content')
<div class="d-flex">
    <div>
        @if(!empty($category))
        <h3>「{{$word}}」、カテゴリ「{{$category}}」の検索結果：{{count($items)}}件</h3>
        @else
        <h3>「{{$word}}」の検索結果：{{count($items)}}件</h3>
        @endif
    </div>
    <div class="ml-auto">
        <div class="btn-group">
            {{-- @sortablelink('title','タイトル順','', ['class' => 'btn btn-outline-secondary'])
            @sortablelink('view','閲覧数','', ['class' => 'btn btn-outline-secondary'])
            @sortablelink('created_at','作成日順','', ['class' => 'btn btn-outline-secondary']) --}}
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

@foreach($items as $item)
<div class="card my-3">
    <div class="card-header d-inline-flex pb-0">
        <h3><a href="article/id={{$item->id}}">{{$item->title}}</a></h3>
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
            作成者：{{$item->author->name}}&nbsp;
        </p>
    </div>
</div>
@endforeach
{{$items->appends(request()->query())->links()}}
@endsection

{{-- ページネート遷移やソートすると「変数が無い」旨のエラーが出る->セッションで変数維持しなければダメか --}}
