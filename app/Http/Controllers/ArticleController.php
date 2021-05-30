<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TestRequest;

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

    public function show(Request $request){
        $items = Article::where('open', 1)->with('author')->get();
        return view('layouts.index', ['items' => $items]);
    }

    public function myArticles(Request $request){
        $items = Article::where('author_id', Auth::user()->id)->get();
        return view('layouts.mypage', ['items' => $items]);
    }
    
    public function article($id){
        $article = Article::find($id);
        $article->increment('view');
        return view('layouts.article',['article' => $article]);
    }

    public function addOrDraft(TestRequest $request){
        if($request->proc == 'add'){
            $article = new Article;
            $article->title = $request->title;
            $article->body = $request->body;
            $article->view = 0;
            $article->author_id = Auth::user()->id;
            $article->open = 1;
            $article->created_at = Carbon::now('Asia/Tokyo');
            $article->save();

            return redirect('/mypage');
        }else{
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

    public function edit(Request $request){
        $article = Article::find($request->id);
        return view('layouts.update', ['form' => $article]);
    }

    public function update(TestRequest $request){
        if($request->proc == 'update'){
            $article = Article::find($request->id);
            $article->title = $request->title ;
            $article->body = $request->body;
            $article->updated_at = Carbon::now('Asia/Tokyo');
            $article->open = 1;
            $article->save();

            return redirect('/mypage');
        }else{
            $article = Article::find($request->id);
            $article->title = $request->title ;
            $article->body = $request->body;
            $article->open = 0;
            $article->save();

            return redirect('/mypage');
        }
    }

    public function open(Request $request){
        $article = Article::find($request->id);
        $article->open = 1;
        $article->save();

        return redirect('/mypage');
    }

    public function close(Request $request){
        $article = Article::find($request->id);
        $article->open = 0;
        $article->save();
        
        return redirect('/mypage');
    }

    public function delete(Request $request){
        $article = Article::find($request->id);
        $article->delete();

        return redirect('/index');
    }

    public function search(Request $request){
        $article = Article::where('title', 'like', "%$request->search%")->get();
        return view('layouts.result', ['word' => $request->search, 'items' => $article]);
    }
}
