<style>
  .mail, .password {display:block; width:300px;margin:0 0 10px 0; margin:10px auto; padding:2px;}
  .login {cursor:pointer; display:block; width:200px; padding:10px;margin:10px auto; text-align:center; border: 1px solid #999;background:#f2f2f2; border-radius:7px; box-sizing: border-box; line-height: 1.5;}
</style>

@extends('layouts.template')

@section('title','ログイン')

@section('content')
  @if(isset($error))
    <p style="color:red;">{{$error}}</p>
    <br>
  @endif
  <form action="auth" method="post">
    @csrf
    <div class="form">
      <input type="text" class="mail" placeholder="メールアドレス" name="email">
      <input type="password" class="password" placeholder="パスワード" name="password"> 
    </div>
    <input type="submit" class="login" value="ログイン">
  </form>
@endsection