<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduct extends FormRequest
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
        $rules = [
            "name" => ['required', ],
            "marque_id" => ["required", "exists:marques,id"],
            "category" => ["required", "in:unite,kilo_ou_carton"],
            "price_kilo_min" => ["required_if:category,kilo_ou_carton", "numeric"],
            "price_kilo_max" => ["required_if:category,kilo_ou_carton", "numeric", "gte:price_kilo_min"],
            "price_carton_min" => ["required_if:category,kilo_ou_carton", "numeric"],
            "price_carton_max" => ["required_if:category,kilo_ou_carton", "numeric", "gte:price_carton_min"],
            "price_unit_min" => ["required_if:category,unite", "numeric"],
            "price_unit_max" => ["required_if:category,unite", "numeric", "gte:price_unit_min"],
        ];
        return $rules;
    }
}
