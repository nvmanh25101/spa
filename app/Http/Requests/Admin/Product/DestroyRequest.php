<?php

namespace App\Http\Requests\Admin\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            [
                'product' => [
                    'required',
                    Rule::exists(Product::class, 'id'),
                ],
            ]
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'product' => $this->route('product')->id
        ]);
    }

    public function attributes(): array
    {
        return [
            'product' => 'Sản phẩm',
        ];
    }

    public function messages(): array
    {
        return [
            'product.required' => ':attribute không được để trống',
            'product.exists' => ':attribute không tồn tại',
        ];
    }
}
