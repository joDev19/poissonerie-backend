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
            "date" => ["required", "date"],
            "contains_gros" => ["required", "boolean"],
            "is_paid" => ["required", "boolean", "accepted_if:type,au comptant"],
            "buyer_informations" => ["required_if:contains_gros,true", "array"],
            "buyer_informations.nom" => ["required_if:contains_gros,true",],
            "buyer_informations.ifu" => ["required_if:contains_gros,true",],
            "selled_products" => ["required", "array"],
            "selled_products.*.product_id" => ["required", "exists:products,id"],
            "selled_products.*.type" => ["required", "in:gros,detail"],
            "selled_products.*.quantity" => ["required", "numeric"],
            "selled_products.*.quantity_per_box" => ["required_if:selled_products.*.type,gros"],
            "selled_products.*.sell_price" => ["required","numeric"],
            "price" => ["required", "numeric"],
            "type" => ["required", "in:à terme,au comptant"],
            "amount_paid" => ["required", "numeric", "min_digits:1"]
        ];
    }
}
