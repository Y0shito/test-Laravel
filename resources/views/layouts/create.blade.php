<style>
  .title{width:100%; font-size:15px; padding:5px; margin:0 0 10px 0; border:solid 1px #696969; border-radius:7px}
  textarea {width:100%; height:300px; font-size:15px; padding:5px; font-family:"ヒラギノ角ゴ ProN"; margin:0 0 10px 0; border:solid 1px #696969; border-radius:7px; resize:vertical;}
  .add, .draft {cursor:pointer; display:block; width:300px; padding:10px; margin:10px auto; text-align:center; border: 1px solid #999; border-radius:7px; box-sizing: border-box; line-height: 1.5;}
    .add {background:#AFFAF8;}
    .draft {background:#f2f2f2;}
</style>

@extends('layouts.template')

@section('content')
  @if(count($errors) > 0)
    <div class="errors">
        @foreach($errors->all() as $error)
          <p>{{$error}}</p>
        @endforeach
    </div>
  @endif
  <br>
  <form action="create" method="post">
    @csrf
    タイトル<input type="text" class="title" name="title" value="{{old('title')}}">
    本文<textarea name="body">{{old('body')}}</textarea>

    <input type="hidden" name="proc" value="add">
    <input type="submit" class="add" value="投稿">

    <input type="hidden" name="proc" value="draft">
    <input type="submit" class="draft" value="下書きへ保存">
  </form>
@endsection