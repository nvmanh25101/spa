<?php

namespace App\Http\Requests\Admin\Order;

use App\Enums\OrderPaymentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'admin_id' => [
                'nullable',
                'integer',
                'exists:admins,id',
            ],
            'status' => [
                'nullable',
                'integer',
            ],
            'payment_status' => [
                'nullable',
                'integer',
                Rule::in(OrderPaymentStatusEnum::asArray()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'admin_id.integer' => ':attribute phải là số nguyên',
            'admin_id.exists' => ':attribute không tồn tại',
            'status.required' => 'Vui lòng nhập :attribute',
            'status.integer' => ':attribute không chính xác',
            'status.in' => ':attribute không hợp lệ.',
            'payment_status.required' => 'Vui lòng nhập :attribute',
            'payment_status.integer' => ':attribute không chính xác',
            'payment_status.in' => ':attribute không hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'admin_id' => 'Nhân viên',
            'status' => 'Trạng thái',
            'payment_status' => 'Trạng thái thanh toán',
        ];
    }
}
