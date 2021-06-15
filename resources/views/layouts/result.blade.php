<?php

// dd($x)
?>
@extends('layouts.template')

@section('title','検索結果')

@section('content')
<div class="d-flex">
    <div>
        <h3>「{{$word}}」の検索結果（{{count($items)}}件）</h3>
    </div>
    {{-- <div class="ml-auto">
        <div class="btn-group">
            <a class="btn btn-outline-secondary" href="/result?sort=id">ID昇順</a>
            <a class="btn btn-outline-secondary" href="/result?sort=title">タイトル順</a>
            <a class="btn btn-outline-secondary" href="/result?sort=view">閲覧数順</a>
            <a class="btn btn-outline-secondary" href="/result?sort=created_at">投稿日順</a>
        </div>
    </div> --}}
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
{{$items->appends(['sort' => $sort])->links()}}
@endsection

{{-- ページネート遷移やソートすると「変数が無い」旨のエラーが出る->セッションで変数維持しなければダメか --}}
