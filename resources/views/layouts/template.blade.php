<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
  <style>
    * {margin:0; padding:0;}
    body {font-family:"ヒラギノ角ゴ ProN";}
    .content {padding:0px 80px;}
    .errors {color:red;}
    header {display:flex; justify-content:space-between; color:white; align-items:center;  background-color:black; width:100%; height:60px;}
      header p {font-size:20pt; padding:0 0 0 80px;}
      header a {color:white; text-decoration:none;}
      header ul {display:flex; list-style:none; padding:0 80px 0 0;}
      header li {padding:0 0 0 20px;}
      h3 {font-size:20pt;}
    footer {font-size:10pt; color:white; text-align:center; background-color:black; width:100%; height:30px;}
  </style>
</head>
<body>
  <header>
    <p><a href="/index">BBS</a></p>
    <ul>
      <li>
        <form action="result" method="post">
          @csrf
          <input type="text" placeholder="記事を検索" name="search">
          <input type="submit" value="検索">
        </form>
      </li>
    @if(Auth::check())
      <li><a href="/create">記事作成</a></li>
      <li><a href="/mypage">マイページ</a></li>
     @else
      <li><a href="/auth">ログイン</a></li>
      <li><a href="/register">登録</a></li>
    @endif
    </ul>
  </header>
  <div class="content">
    @yield('content')
  </div>
  <footer>
    <p>Copyright 2021 Y.<p>
  </footer>
</body>
</html>