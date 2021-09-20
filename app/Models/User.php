<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $info = Info::updateOrCreate(
                ['user_id' => $user->id],
                ['introduction' => '', 'link_name' => '', 'url' => '']
            );
        });

        static::deleting(function ($user) {
            $user->getBookmarked()->delete(); // 自身の記事にbmされているのを削除
            $user->getBookmarks()->delete(); //自身のブックマークを削除
            $user->articles()->delete();
            $user->getInfo()->delete();
            $user->getFollows()->detach();
            $user->getFollowers()->detach();
        });
    }

    public function articles()
    {
        return $this->hasMany('App\Models\Article', 'author_id');
    }

    public function getBookmarks()
    {
        return $this->hasMany('App\Models\Bookmark', 'user_id');
    }

    public function getBookmarked()
    {
        return $this->hasManyThrough(
            'App\Models\Bookmark',
            'App\Models\Article',
            'author_id',
            'article_id',
        );
    }

    public function getFollows()
    {
        return $this->belongsToMany('App\Models\User', 'follow', 'user_id', 'follow_id');
    }

    public function getFollowers()
    {
        return $this->belongsToMany('App\Models\User', 'follow', 'follow_id', 'user_id');
    }

    public function getInfo()
    {
        return $this->hasOne('App\Models\Info');
    }
}
