<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body style="padding-top:70px">
    <div class="navbar fixed-top navbar-expand navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/index">BBS</a>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav">
                    @if(Auth::check())
                    <a class="nav-item nav-link" href="/create">記事作成</a>
                    <a class="nav-item nav-link" href="/mypage">マイページ</a>
                    @else
                    <a class="nav-item nav-link" href="/auth">ログイン</a>
                    <a class="nav-item nav-link" href="/register">登録</a>
                    @endif
                </div>
            </div>

            <form class="form-inline" action="result" method="post">
                @csrf
                <input class="form-control mr-sm-2" type="text" placeholder="記事を検索" aria-label="Search" name="search">
                <button class="btn btn-success my-2 my-sm-0" type="submit">検索</button>
            </form>
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>

    <footer class="position-sticky py-4 bg-primary text-light">
        <div class="container text-center">
            <p>Copyright 2021 Y.</p>
        </div>
    </footer>
</body>

</html>
