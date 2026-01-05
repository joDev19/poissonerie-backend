<?php

namespace App\Http\Requests;
use App\Rules\RequiredIfProductIsKilo;
use App\Rules\RequiredIfProductIsUnite;
use Illuminate\Foundation\Http\FormRequest;

class CreateEntrer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return checkIfUserIsAdmin();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $requiredIfProductIsKilo = new RequiredIfProductIsKilo();
        $requiredIfProductIsUnite = new RequiredIfProductIsUnite();
        $requiredIfProductIsKilo->setData(['product_id' => $this->data('product_id')]);
        $requiredIfProductIsUnite->setData(['product_id' => $this->data('product_id')]);
        return [
            "date" => ["required", "date"],
            "product_id" => ["required", "exists:products,id"],
            "price" => ["required", "numeric"],
            "fournisseur_id" => ["required", "exists:fournisseurs,id"],
            // si category kilo_ou_carton
            "box_quantity" => [$requiredIfProductIsKilo,"numeric"],
            "kilo_once_quantity" => [$requiredIfProductIsKilo,"numeric"],
            //"price_carton_min" => [$requiredIfProductIsKilo, "numeric"],
            //"price_carton_max" => [$requiredIfProductIsKilo, "numeric","gte:price_carton_min"],
            // si product unite
            "unit_quantity" => [$requiredIfProductIsUnite,"numeric"],
        ];
    }
}
