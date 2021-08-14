<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Models\Article;
use App\Models\Bookmark;
use App\Models\Info;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function show(Request $request)
    {
        $items = Article::where('open', 1)->with('author')->sortable()->paginate(7);
        return view('layouts.index', ['items' => $items]);
    }

    public function myArticles(Request $request)
    {
        $items = Article::where('author_id', Auth::user()->id)->paginate(5, ['*'], 'articles');
        $bookmarks = Bookmark::where('user_id', Auth::user()->id)->paginate(5, ['*'], 'bookmarks');
        $info = Info::where('user_id', Auth::id())->first();
        return view('layouts.mypage', ['items' => $items, 'bookmarks' => $bookmarks, 'info' => $info]);
    }

    public function article($id)
    {
        $article = Article::find($id);
        $article->increment('view');
        return view('layouts.article', ['article' => $article]);
    }

    public function toPreview(TestRequest $request)
    {
        // 文字列の前後にある空白、改行等の削除
        $pattern = '/\A[\p{Cc}\p{Cf}\p{Z}]++|[\p{Cc}\p{Cf}\p{Z}]++\z/u';

        $title = preg_replace($pattern, '', $request->title);
        $body = preg_replace($pattern, '', $request->body);

        $request->session()->put('title', $title);
        $request->session()->put('body', $body);

        return redirect('/preview');
    }

    public function fromPreview()
    {
        return view('layouts.preview');
    }

    public function add(Request $request)
    {
        $article = new Article;
        $article->title = $request->session()->get('title');
        $article->body = $request->session()->get('body');
        $article->view = 0;
        $article->author_id = Auth::id();
        $article->open = 1;
        $article->created_at = Carbon::now('Asia/Tokyo');
        $article->category = $request->category;
        $article->save();

        $request->session()->forget(['title', 'body']);

        return redirect('/mypage');
    }

    public function draft(Request $request)
    {
        $article = new Article;
        $article->title = $request->session()->get('title');
        $article->body = $request->session()->get('body');
        $article->view = 0;
        $article->author_id = Auth::id();
        $article->open = 0;
        $article->created_at = Carbon::now('Asia/Tokyo');
        $article->save();

        $request->session()->forget(['title', 'body']);

        return redirect('/mypage');
    }

    public function edit(Request $request)
    {
        $article = Article::find($request->id);
        return view('layouts.update', ['form' => $article]);
    }

    public function update(TestRequest $request)
    {
        $article = Article::find($request->id);
        $article->title = $request->title;
        $article->body = $request->body;
        $article->updated_at = Carbon::now('Asia/Tokyo');
        $article->open = 1;
        $article->save();

        return redirect('/mypage');
    }

    public function reDraft(TestRequest $request)
    {
        $article = Article::find($request->id);
        $article->title = $request->title;
        $article->body = $request->body;
        $article->open = 0;
        $article->save();

        return redirect('/mypage');
    }

    public function open(Request $request)
    {
        $article = Article::find($request->id);
        $article->open = 1;
        $article->save();

        return redirect('/mypage');
    }

    public function close(Request $request)
    {
        $article = Article::find($request->id);
        $article->open = 0;
        $article->save();

        return redirect('/mypage');
    }

    public function delete(Request $request)
    {
        $article = Article::find($request->id);
        $article->delete();

        return redirect('/index');
    }

    public function search(Request $request)
    {
        $query = Article::where('open', 1);
        $words = preg_split('/[\p{Z}\p{Cc}]++/u', $request->search, 3, PREG_SPLIT_NO_EMPTY);
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
