<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class CreateVente extends FormRequest
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
            "product_id" => ["required", "exists:products,id"],
            "type" => ["required", "in:gros,detail"],
            "quantity" => ["required", "numeric"],
            "buyer_id" => ["nullable", "exists:buyers,id"],
            "date" => ["required", "date"],
            "buyer_informations" => ["nullable", "json", "contains:name,email,contact,ville"]
        ];
    }
}
