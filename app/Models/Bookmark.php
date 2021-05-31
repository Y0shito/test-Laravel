<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    public function articles(){
        return $this->belongsTo('App\Models\Article', 'article_id', 'id');
    }
}
