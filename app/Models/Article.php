<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $dates = [
        'created_at',
        'updated_at'
    ];
    
    public function author(){
        return $this->belongsTo('App\Models\User');
    }
}
