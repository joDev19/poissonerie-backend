<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkIfUserIsAdmin();;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ['required', 'unique:products,name'],
            "marque_id" => ["required", "exists:marques,id"],
            "category" => ["required", "in:unite,kilo_ou_carton"],
            "price_kilo" => ["required_if:category,kilo_ou_carton", "numeric"],
            "price_carton" => ["required_if:category,kilo_ou_carton", "numeric"],
            "price_unit" => ["required_if:category,unite", "numeric"],
        ];
    }
}
