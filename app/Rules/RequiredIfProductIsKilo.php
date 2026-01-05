<?php

namespace App\Rules;

use App\Services\ProductService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class RequiredIfProductIsKilo implements ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    // ...

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //dd($attribute, $value, $this->data);
        $productService = new ProductService();
        if (isset($this->data['product_id']) &&$this->data['product_id'] != null) {
            $product = $productService->find($this->data['product_id']);
            if ($product->isKiloOuCarton() && (is_null($value) || $value === '')) {
                $fail('The :attribute field is required when the product is sold by kilo or carton.');
            }

        }

    }
}
