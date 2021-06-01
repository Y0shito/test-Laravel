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
    <p>
      閲覧数：{{$item->view}}&nbsp;
      @if(isset($item->updated_at))
        更新日：{{$item->updated_at->format('Y年m月d日') }}&nbsp;
      @else
        作成日：{{$item->created_at->format('Y年m月d日') }}&nbsp;
      @endif
      ブックマーク数：{{$item->bookmarks->count()}}
    </p>

    <br>
    <hr color="blue">
    <br>
  @endforeach

  <h3>ブックマークした記事：{{count($bookmarks)}}件</h3>
  <br>
  @foreach ($bookmarks as $bookmark)
    <h3><a href="article/id={{$bookmark->articles->id}}">{{$bookmark->articles->title}}</a></h3>
    <p>{{$bookmark->articles->body}}</p>
    <br>
    <p>
      閲覧数：{{$bookmark->articles->view}}&nbsp;
      @if(isset($bookmark->articles->updated_at))
        更新日：{{$bookmark->articles->updated_at->format('Y年m月d日') }}&nbsp;
      @else
        作成日：{{$bookmark->articles->created_at->format('Y年m月d日') }}&nbsp;
      @endif
      作成者：{{$bookmark->articles->author->name}}&nbsp;
    </p>
    <br>
    <hr color="green">
    <br>
  @endforeach
@endsection