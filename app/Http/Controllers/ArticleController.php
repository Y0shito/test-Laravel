<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Models\Article;
use App\Models\Bookmark;
use App\Models\Info;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function show(Request $request)
    {
        $items = Article::where('open', 1)->with('author')->sortable()->paginate(10);
        return view('layouts.index', compact('items'));
    }

    public function myArticles(Request $request)
    {
        $user = Auth::id();

        $items = Article::where('author_id', $user)->paginate(7, ['*'], 'articles');
        $bookmarks = Bookmark::where('user_id', $user)->paginate(7, ['*'], 'bookmarks');
        $info = Info::where('user_id', $user)->first();
        $follows = User::find($user)->findFollows()->get();
        $followers = User::find($user)->findFollowers()->get();
        return view('layouts.mypage', compact('items', 'bookmarks', 'info', 'follows', 'followers'));
    }

    public function article($id)
    {
        $article = Article::find($id);
        $article->increment('view');
        return view('layouts.article', compact('article'));
    }

    public function create()
    {
        return view('layouts.create');
    }

    public function edit(Request $request)
    {
        $article = Article::find($request->id);
        return view('layouts.create', compact('article'));
    }

    public function toPreview(TestRequest $request)
    {
        // 文字列の前後にある空白、改行等の削除
        $pattern = '/\A[\p{Cc}\p{Cf}\p{Z}]++|[\p{Cc}\p{Cf}\p{Z}]++\z/u';

        $title = preg_replace($pattern, '', $request->title);
        $body = preg_replace($pattern, '', $request->body);

        session(compact('title', 'body'), ['article_id' => $request->article_id]);

        return redirect('/preview');
    }

    public function fromPreview()
    {
        return view('layouts.preview');
    }

    public function add(Request $request)
    {
        $article = Article::updateOrCreate(
            ['id' => session('article_id')],
            [
                'title' => session('title'),
                'body' => session('body'),
                'author_id' => Auth::id(),
                'open' => 1,
                'category' => $request->category,
            ]
        );

        $request->session()->forget(['title', 'body', 'article_id']);
        return redirect('/mypage');
    }

    public function draft(Request $request)
    {
        $article = Article::updateOrCreate(
            ['id' => session('article_id')],
            [
                'title' => session('title'),
                'body' => session('body'),
                'author_id' => Auth::id(),
                'open' => 0,
                'category' => $request->category,
            ]
        );

        $request->session()->forget(['title', 'body', 'article_id']);
        return redirect('/mypage');
    }

    // add,draftでcreated_at,updated_atが同時かつ同じ値で記録される
    // 新規投稿時はcreated_at,更新時はupdated_tを処理したい

    public function open(Request $request)
    {
        Article::find($request->id)->update(['open' => 1]);
        return redirect('/mypage');
    }

    public function close(Request $request)
    {
        Article::find($request->id)->update(['open' => 0]);
        return redirect('/mypage');
    }

    public function delete(Request $request)
    {
        Article::destroy($request->id);
        return redirect('/mypage');
    }

    public function search(Request $request)
    {
        $query = Article::where('open', 1);
        $words = preg_split('/[\p{Z}\p{Cc}]++/u', $request->search, 5, PREG_SPLIT_NO_EMPTY);
        $category = $request->category;

        if (!empty($category)) {
            $query->where('category', 'like', "{$category}");
        }

        if (!empty($words)) {
            foreach ($words as $word) {
                if (preg_match('/-/', $word) == 0) {
                    $query->where('title', 'like', "%$word%");
                } else if (preg_match('/-/', $word) == 1) {
                    $query->where('title', 'not like', '%' . preg_replace('/-/', '', $word) . '%');
                }
            }
        }

        $article = $query->sortable()->paginate(10);
        return view('layouts.result', ['word' => $request->search, 'items' => $article, 'category' => $category]);
    }
}
