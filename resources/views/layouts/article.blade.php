@extends('layouts.template')

@section('title','')

@section('top_title','')

@section('content')
<h3>{{$article->title}}</h3>
<br>
<p>
  閲覧数：{{$article->view}}&nbsp;
  @if(isset($article->updated_at))
    更新日：{{$article->updated_at->format('Y年m月d日')}}&nbsp;
  @endif
  作成日：{{$article->created_at->format('Y年m月d日')}}
  作成者：{{$article->author->name}}&nbsp;
  ブックマーク数：{{$article->bookmarks->count()}}
</p>
<br>
<p>{{$article->body}}</p>
<br>
@endsection