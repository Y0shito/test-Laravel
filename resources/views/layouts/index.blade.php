<style>
  .sort{display:flex; justify-content:space-between; align-items:center;}
  .sort ul {display:flex; list-style:none;}
  .sort li {padding:0 0 0 20px;}
</style>

@extends('layouts.template')

@section('title','TOP')

@section('content')
  <br>
  <div class="sort">
    <h3>記事閲覧</h3>
    <ul>
      <li><a href="/index?sort=id">ID昇順</a></li>
      <li><a href="/index?sort=title">タイトル順</a></li>
      <li><a href="/index?sort=view">閲覧数順</a></li>
      <li><a href="/index?sort=created_at">投稿日順</a></li>
    </ul>
  </div>
  <br>
  @foreach($items as $item)
    <h3><a href="article/id={{$item->id}}">{{$item->title}}</a></h3>
    <p>{{$item->body}}</p>
    <br>
    <p>閲覧数：{{$item->view}}&nbsp;
      @if(isset($item->updated_at))
        更新日：{{$item->updated_at->format('Y年m月d日') }}&nbsp;
      @else
        作成日：{{$item->created_at->format('Y年m月d日') }}&nbsp;
      @endif
    作成者：{{$item->author->name}}
    </p>
    <!-- <form action="edit" method="POST">
      @csrf
      <input type="hidden" name="id" value="{{$item->id}}">
      <input type="submit" value="編集">
    </form>
    <form action="delete" method="POST">
      @csrf
      <input type="hidden" name="id" value="{{$item->id}}">
      <input type="submit" value="削除">
    </form>  -->
    <br>
    <hr color="blue">
    <br>
  @endforeach
<!-- ① -->
@endsection