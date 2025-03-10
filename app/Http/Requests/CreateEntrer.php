<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEntrer extends FormRequest
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
        return [
            "date" => ["required", "date"],
            "product_id" => ["required", "exists:products,id"],
            "price" => ["required", "numeric"],
            "box_quantity" => ["required","numeric"],
            "kilo_quantity" => ["required","numeric"],
            "fournisseur_id" => ["required", "exists:fournisseurs,id"]
        ];
    }
}
