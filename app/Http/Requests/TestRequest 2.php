<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
{
    public function authorize(){
            return true;
    }

    public function rules(){
        return [
            'title' => 'required | between:5,30',
            'body' => 'required | between:30,1000'
        ];
    }

    public function messages(){
        return [
            'title.required' => 'タイトルを入力してください',
            'title.between' => 'タイトルを5文字以上、30文字以下で入力してください',
            'body.required' => '本文を入力してください',
            'body.between' => '本文を30文字以上、1000文字以下で入力してください'
        ];
    }
}
