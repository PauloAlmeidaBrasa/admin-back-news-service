<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd(__LINE__);
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ];
    }
    public function messages(): array
    {
        // dd(__LINE__);

        return [
            'email.required' => 'Email is required',
            'email.email' => 'Valid email is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
        ];
    }

    public function successResponse($data = null, string $message = '', int $code = 200)
    {
        dd(__LINE__);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    public function errorResponse(string $message, int $code = 400, $errors = null)
    {
        dd(__LINE__);

        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], $code)
        );
    }
}
