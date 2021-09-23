<?php

namespace App\Models;

use App\Enums\PublicStatus;
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

    public function author()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function bookmarks()
    {
        return $this->hasMany('App\Models\Bookmark');
    }

    public function scopeOpenArticles($query)
    {
        $query->where('open', PublicStatus::OPEN);
    }
}
