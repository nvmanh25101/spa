<?php

namespace App\Http\Requests\Admin\Appointment;

use App\Enums\AppointmentStatusEnum;
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
            'date' => [
                'required',
                'date_format:d-m-Y',
            ],
            'time_id' => [
                'required',
                'integer',
                'exists:times,id',
            ],
            'admin_id' => [
                'nullable',
                'integer',
                'exists:admins,id',
            ],
            'status' => [
                'required',
                'integer',
                Rule::in(AppointmentStatusEnum::asArray()),
            ],
            'voucher_id' => [
                'nullable',
                'integer',
                'exists:vouchers,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Vui lòng nhập :attribute',
            'date.date_format' => ':attribute không đúng định dạng dd-mm-yyyy',
            'date.after_or_equal' => ':attribute phải lớn hơn hoặc bằng ngày hôm nay',
            'time_id.required' => 'Vui lòng nhập :attribute',
            'time_id.integer' => ':attribute phải là số nguyên',
            'time_id.exists' => ':attribute không tồn tại',
            'admin_id.integer' => ':attribute phải là số nguyên',
            'admin_id.exists' => ':attribute không tồn tại',
            'status.required' => 'Vui lòng nhập :attribute',
            'status.integer' => ':attribute không chính xác',
            'status.in' => ':attribute không hợp lệ.',
            'voucher_id.integer' => ':attribute phải là số nguyên',
            'voucher_id.exists' => ':attribute không tồn tại',
        ];
    }

    public function attributes(): array
    {
        return [
            'date' => 'Ngày đặt',
            'time_id' => 'Check-in',
            'admin_id' => 'Nhân viên',
            'status' => 'Trạng thái',
            'voucher_id' => 'Mã giảm giá',
        ];
    }
}
