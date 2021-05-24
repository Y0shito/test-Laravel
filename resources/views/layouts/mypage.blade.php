<style>
  .line {display:flex; justify-content:space-between; align-items:center;}
  .line .Button {display:flex;}
</style>

@extends('layouts.template')

@section('title','マイページ')

@section('content')
  <h3>マイページ</h3>
  <form action="mypage" method="post">
    @csrf
    <input type="submit" value="ログアウト">
  </form>
  <br>
  <h3>公開中の記事：{{count($items)}}件</h3>

  @foreach($items as $item)
    <div class="line">
      <h3><a href="article/id={{$item->id}}">{{$item->title}}</a></h3>

      <div class="Button">
        <form action="edit" method="POST">
          @csrf
          <input type="hidden" name="id" value="{{$item->id}}">
          <input type="submit" value="編集">
        </form>

        @if($item->open == 1)
          <form action="close" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{$item->id}}">
            <input type="submit" value="非公開にする">
          </form>
        @else
          <form action="open" method="POST">
              @csrf
              <input type="hidden" name="id" value="{{$item->id}}">
              <input type="submit" value="公開する">
            </form>
        @endif

        <form action="delete" method="POST">
          @csrf
          <input type="hidden" name="id" value="{{$item->id}}">
          <input type="submit" value="削除">
        </form>
      </div>
    </div>

    <p>{{$item->body}}</p>
    <br>
    <p>閲覧数：{{$item->view}}&nbsp;
        作成日：{{$item->created_at->format('Y年m月d日') }}&nbsp;
        @if(isset($item->updated_at))
          更新日：{{$item->updated_at->format('Y年m月d日') }}
        @endif
    </p>

    <br>
    <hr color="blue">
    <br>
  @endforeach
@endsection