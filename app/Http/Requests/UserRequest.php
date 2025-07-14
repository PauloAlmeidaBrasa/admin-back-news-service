<?php

// app/Http/Requests/LoginRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
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
            case 'users.all':
                break;
            case 'users.store':
                $rules = [
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6',
                    'name' => 'required|string|max:18'
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
            'client_id' => 'Client ID is require',
            'email.required' => 'Email is required',
            // 'email.email' => 'Must be a valid email',
            // 'password.required' => 'Password is required',
            // 'password.min' => 'Password must be at least 8 characters',
        ];
    }
}