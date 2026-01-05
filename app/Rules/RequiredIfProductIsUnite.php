<?php

namespace App\Rules;

use App\Services\ProductService;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class RequiredIfProductIsUnite implements ValidationRule, DataAwareRule
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
        if (isset($this->data['product_id']) && $this->data['product_id'] != null) {
            $productService = new ProductService();
            $product = $productService->find($this->data['product_id']);
            if ($product && $product->isUnite() && (is_null($value) || $value === '')) {
                $fail('The :attribute field is required when the product is sold by unit.');
            }
        }
    }
}
