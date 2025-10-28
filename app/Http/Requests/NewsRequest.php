<?php

// app/Http/Requests/LoginRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class NewsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $routeName = $this->route()->getName();

        $rules = [];

        switch ($routeName) {
            case 'news.all':
            break;
            case 'news.store':
                $rules = [
                    'title' => 'required',
                    'category' => 'required',
                ];
            break;
              case 'news.delete':
                $rules = [
                    'news_ID' => 'required',
                ];
            break;
        }
        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    public function messages()
    {
        return [
            'title.required' => 'news title is missing',
            'category.required' => 'category ID is missing',
            'news_id.required' => 'news id is missing'
        ];
    }
}