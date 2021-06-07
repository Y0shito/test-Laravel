@extends('layouts.template')

@section('title','ログイン')

@section('content')
@if(isset($error))
<p class="text-danger">{{$error}}</p>
@endif

<form action="auth" method="post">
    @csrf
    <div class="form-group d-flex justify-content-center mb-3">
        <input type="text" class="form-control col-md-4" placeholder="メールアドレス" name="email">
        <input type="password" class="form-control col-md-4" placeholder="パスワード" name="password">

        <input type="submit" class="btn btn-primary" value="ログイン">
    </div>
</form>
@endsection
