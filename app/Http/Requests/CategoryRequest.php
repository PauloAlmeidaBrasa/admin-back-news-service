<?php

// app/Http/Requests/LoginRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CategoryRequest extends FormRequest
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
            case 'category.all':
            break;
            case 'category.store':
                $rules = [
                    'name' => 'required',
                    'description' => 'nullable',
                ];
            break;
              case 'category.delete':
                $rules = [
                    'category_ID' => 'required',
                ];
            break;
            case 'category.update':
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
            'category_ID.required' => 'Category ID is missing',
            'name.required' => 'name of category is missing'
            // 'category.required' => 'category ID is missing',
        ];
    }
}