@extends('layouts.template')

@section('title','確認')

@section('top_title','内容確認')

@section('content')
<h4>タイトル：{{$title}}</h4>
<p>本文：{{$body}}</p>
<br>
<p><a href="create">戻る</a></p>
@endsection