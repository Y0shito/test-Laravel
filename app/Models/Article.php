<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Article extends Model
{
    use HasFactory;
    use Sortable;

    // public $timestamps = false;
    public $sortable = ['title', 'view', 'created_at'];

    protected $fillable = ['title', 'body', 'author_id', 'open', 'category'];
    protected $dates = ['created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($article) {
            // 削除されたユーザーの記事がブックマークされていたら削除
            // またブックマークされてた記事が削除されたらbmも削除
            $article->chainBookmarks()->delete();
        });
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function bookmarks()
    {
        return $this->hasMany('App\Models\Bookmark');
    }

    public function chainBookmarks()
    {
        return $this->hasMany('App\Models\Bookmark', 'article_id');
    }
}
