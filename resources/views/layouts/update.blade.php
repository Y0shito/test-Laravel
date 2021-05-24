<style>
  .title {width:100%; font-size:15px; padding:5px; margin:0 0 10px 0; border:solid 1px #696969; border-radius:7px}
  textarea {width:100%; height:300px; font-size:15px; padding:5px; font-family:"ヒラギノ角ゴ ProN"; margin:0 0 10px 0; border:solid 1px #696969; border-radius:7px; resize:vertical;}
  .update, .draft {cursor:pointer; display:block; width:300px; padding:10px; margin:10px auto; text-align:center; border: 1px solid #999; border-radius:7px; box-sizing: border-box; line-height: 1.5;}
    .update {background:#AFFAF8;}
    .draft {background:#f2f2f2;}
</style>

@extends('layouts.template')

@section('title','記事更新')

@section('content')
  @if(count($errors) > 0)
    <div class="errors">
        @foreach($errors->all() as $error)
          <p>{{$error}}</p>
        @endforeach
    </div>
  @endif
  <br>
  <form action="update" method="post">
    @csrf
    <input type="hidden" name="id" value="{{$form->id}}">

    タイトル<input type="text" class="title" name="title" value="{{$form->title}}">
      
    内容<textarea name="body">{{$form->body}}</textarea>

    <input type="hidden" name="proc" value="update">
    <input type="submit" class="update" class="update" value="変更して公開">

    <input type="hidden" name="proc" value="draft">
    <input type="submit" class="draft" value="下書きへ保存">
  </form>
@endsection