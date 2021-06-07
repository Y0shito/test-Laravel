<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Article;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    // public function show(Request $request){
    //     $sort = $request->sort;
    //     $items = Article::orderBy($sort, 'asc')->simplePaginate(7);
    //     $param = [
    //         'items' => $items,
    //         'sort' => $sort
    //     ];
    //     return view('layouts.index', $param);
    // }

    public function show(Request $request)
    {
        $sort = $request->sort;
        $items = Article::where('open', 1)->with('author')->orderBy($sort, 'asc')->paginate(7);
        return view('layouts.index', ['items' => $items, 'sort' => $sort]);
    }

    public function myArticles(Request $request)
    {
        $items = Article::where('author_id', Auth::user()->id)->get();
        $bookmarks = Bookmark::where('user_id', Auth::user()->id)->get();
        return view('layouts.mypage', ['items' => $items, 'bookmarks' => $bookmarks]);
    }

    public function article($id)
    {
        $article = Article::find($id);
        $article->increment('view');
        return view('layouts.article', ['article' => $article]);
    }

    public function addOrDraft(TestRequest $request)
    {
        if ($request->proc == 'add') {
            $article = new Article;
            $article->title = $request->title;
            $article->body = $request->body;
            $article->view = 0;
            $article->author_id = Auth::user()->id;
            $article->open = 1;
            $article->created_at = Carbon::now('Asia/Tokyo');
            $article->save();

            return redirect('/mypage');
        } else if ($request->proc == 'draft'){
            $article = new Article;
            $article->title = $request->title;
            $article->body = $request->body;
            $article->view = 0;
            $article->author_id = Auth::user()->id;
            $article->open = 0;
            $article->created_at = Carbon::now('Asia/Tokyo');
            $article->save();

            return redirect('/mypage');
        }
    }

    public function edit(Request $request)
    {
        $article = Article::find($request->id);
        return view('layouts.update', ['form' => $article]);
    }

    public function update(TestRequest $request)
    {
        if ($request->proc == 'update') {
            $article = Article::find($request->id);
            $article->title = $request->title;
            $article->body = $request->body;
            $article->updated_at = Carbon::now('Asia/Tokyo');
            $article->open = 1;
            $article->save();

            return redirect('/mypage');
        } else if ($request->proc == 'draft'){
            $article = Article::find($request->id);
            $article->title = $request->title;
            $article->body = $request->body;
            $article->open = 0;
            $article->save();

            return redirect('/mypage');
        }
    }
    // 下書き保存やっても公開されてしまう->draft通ってない？
    // addも同様

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
        $sort = $request->sort;
        $article = Article::where('open', 1)->where('title', 'like', "%$request->search%")->orderBy($sort, 'asc')->paginate(7);
        return view('layouts.result', ['word' => $request->search, 'items' => $article, 'sort' => $sort]);
    }
}
