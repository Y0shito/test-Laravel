@extends('layouts.template')

@section('title','検索結果')

@section('content')
  <h3>「{{$word}}」の検索結果（{{count($items)}}件）</h3>
  <br>
  @foreach($items as $item)
    <h3><a href="article/id={{$item->id}}">{{$item->title}}</a></h3>
    <p>{{$item->body}}</p>
    <br>
    <hr color="blue">
    <br>
  @endforeach
@endsection