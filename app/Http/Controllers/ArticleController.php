<?php

namespace App\Http\Controllers;

use App\{Enums\PublicStatus, Http\Requests\TestRequest, Traits\Spaceremoval};
use App\Models\{Article, Bookmark, Info, User};
use Illuminate\{Http\Request, Support\Facades\Auth, Support\Facades\DB};


class ArticleController extends Controller
{
    use Spaceremoval;

    public function show(Request $request)
    {
        $items = Article::openArticles()->with('author')->sortable()->paginate(10);
        return view('layouts.index', compact('items'));
    }

    public function myArticles(Request $request)
    {
        $user = Auth::id();

        $items = Article::where('author_id', $user)->paginate(7, ['*'], 'articles');
        $bookmarks = Bookmark::where('user_id', $user)->paginate(7, ['*'], 'bookmarks');
        $info = Info::where('user_id', $user)->first();
        $follows = User::find($user)->getFollows()->get(); //get被りしているのを直す
        $followers = User::find($user)->getFollowers()->get(); //get被りしているのを直す
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
        $title = ArticleController::spaceRemoval($request->title);
        $body = ArticleController::spaceRemoval($request->body);

        session(compact('title', 'body'), ['article_id' => $request->article_id]);

        return redirect('/preview');
    }

    public function fromPreview()
    {
        return view('layouts.preview');
    }

    public function add(Request $request)
    {
        DB::beginTransaction(); //トランザクションの開始
        try {
            $article = Article::updateOrCreate(
                ['id' => session('article_id')],
                [
                    'title' => session('title'),
                    'body' => session('body'),
                    'author_id' => Auth::id(),
                    'open' => PublicStatus::OPEN,
                    'category' => $request->category,
                ]
            );

            DB::commit(); //データの挿入
            $request->session()->forget(['title', 'body', 'article_id']);
            return redirect('/mypage');
        } catch (\Exception $e) { //例外発生時
            // 本来はここに例外時の処理を書く
            DB::rollback(); //適用前に戻す
        }
    }

    public function draft(Request $request)
    {
        DB::beginTransaction();
        try {
            $article = Article::updateOrCreate(
                ['id' => session('article_id')],
                [
                    'title' => session('title'),
                    'body' => session('body'),
                    'author_id' => Auth::id(),
                    'open' => PublicStatus::CLOSE,
                    'category' => $request->category,
                ]
            );

            DB::commit();
            $request->session()->forget(['title', 'body', 'article_id']);
            return redirect('/mypage');
        } catch (\Exception $e) {
            // 本来はここに例外時の処理を書く
            DB::rollback();
        }
    }

    // add,draftでcreated_at,updated_atが同時かつ同じ値で記録される
    // 新規投稿時はcreated_at,更新時はupdated_tを処理したい

    public function open(Request $request)
    {
        Article::find($request->id)->update(['open' => PublicStatus::OPEN]);
        return redirect('/mypage');
    }

    public function close(Request $request)
    {
        Article::find($request->id)->update(['open' => PublicStatus::CLOSE]);
        return redirect('/mypage');
    }

    public function delete(Request $request)
    {
        Article::destroy($request->id);
        return redirect('/mypage');
    }

    public function search(Request $request)
    {
        $query = Article::openArticles();
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
