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

    public function addOrDraft(Request $request)
    {
        if ($request->proc == 'add') {
            $article = new Article;
            $article->title = $request->session()->get('title');
            $article->body = $request->session()->get('body');
            $article->view = 0;
            $article->author_id = Auth::user()->id;
            $article->open = 1;
            $article->created_at = Carbon::now('Asia/Tokyo');
            $article->save();

            $request->session()->forget(['title', 'body']);

            return redirect('/mypage');
        } else if ($request->proc == 'draft') {
            $article = new Article;
            $article->title = $request->session()->get('title');
            $article->body = $request->session()->get('body');
            $article->view = 0;
            $article->author_id = Auth::user()->id;
            $article->open = 0;
            $article->created_at = Carbon::now('Asia/Tokyo');
            $article->save();

            $request->session()->forget(['title', 'body']);

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
        } else if ($request->proc == 'draft') {
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
        // あらゆる空白を認識し分割
        $words = preg_split('/[\p{Z}\p{Cc}]++/u', $request->search, 2, PREG_SPLIT_NO_EMPTY);
        // -の付く単語を検索
        $index = preg_grep('/-/', $words);

        if (empty($index)) {
            // -単語無ければ通常/AND検索
            if (count($words) == 2) {
                // 配列が2つあればAND検索
                $query = Article::where('open', 1)->where('title', 'like', "%$words[0]%")->where('title', 'like', "%$words[1]%");
            } else if (count($words) == 1) {
                // 通常検索
                $query = Article::where('open', 1)->where('title', 'like', "%$words[0]%");
            } else {
                // 検索用語が無い場合
                return back();
            }
        } else {
            // 除外語句の整形
            $ex = implode(preg_replace('/-/', '', $index));
            // 通常語句の格納
            $word = implode(preg_grep('/-/', $words, PREG_GREP_INVERT));

            $query = Article::where('open', 1)->where('title', 'like', "%$word%")->where('title', 'not like', "%$ex%");
        }

        $article = $query->orderBy($sort, 'asc')->paginate(7);
        return view('layouts.result', ['word' => $request->search, 'items' => $article, 'sort' => $sort]);
    }
}
