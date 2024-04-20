<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        // hanya role customer yang bisa akses 
        return auth()->user()->role === 'customer'; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'listing_id' => 'required|exists:listings,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ];
    }

    // validasi jika gagal 
    protected function failedValidation(Validator $validator)
    {
         $error = (new ValidationException($validator))->errors();
         
         throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validasi error...',
                'data' => $error,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
