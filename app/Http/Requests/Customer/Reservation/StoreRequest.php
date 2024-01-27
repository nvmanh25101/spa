<?php

namespace App\Http\Requests\Customer\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_booker" => [
                'required',
                'string',
                'max:50',
            ],
            "email_booker" => [
                'required',
                'string',
                'max:50',
                'email'
            ],
            "phone_booker" => [
                'required',
                'string',
                'max:15',
            ],
            "number_people" => [
                'required',
                'integer',
                'min:1'
            ],
            "note" => [
                'nullable',
            ],
            'date' => [
                'required',
                'date_format:d-m-Y',
                'after_or_equal:today',
            ],
            'time_id' => [
                'required',
                'integer',
                'exists:times,id',
            ],
            'service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],
            'price_id' => [
                'required',
                'integer',
                'exists:price_services,id',
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
            'name_booker.required' => 'Vui lòng nhập :attribute',
            'name_booker.string' => ':attribute phải là chuỗi',
            'name_booker.max' => ':attribute không được vượt quá :max ký tự',
            'email_booker.required' => 'Vui lòng nhập :attribute',
            'email_booker.string' => ':attribute phải là chuỗi',
            'email_booker.max' => ':attribute không được vượt quá :max ký tự',
            'email_booker.email' => ':attribute không đúng định dạng',
            'phone_booker.required' => 'Vui lòng nhập :attribute',
            'phone_booker.string' => ':attribute phải là chuỗi',
            'phone_booker.max' => ':attribute không được vượt quá :max ký tự',
            'number_people.required' => 'Vui lòng nhập :attribute',
            'number_people.integer' => ':attribute phải là số nguyên',
            'number_people.min' => ':attribute phải lớn hơn hoặc bằng :min',
            'note.string' => ':attribute phải là chuỗi',
            'date.required' => 'Vui lòng nhập :attribute',
            'date.date_format' => ':attribute không đúng định dạng dd-mm-yyyy',
            'date.after_or_equal' => ':attribute phải lớn hơn hoặc bằng ngày hôm nay',
            'time_id.required' => 'Vui lòng nhập :attribute',
            'time_id.integer' => ':attribute phải là số nguyên',
            'time_id.exists' => ':attribute không tồn tại',
            'service_id.required' => 'Vui lòng nhập :attribute',
            'service_id.integer' => ':attribute phải là số nguyên',
            'service_id.exists' => ':attribute không tồn tại',
            'price_id.required' => 'Vui lòng nhập :attribute',
            'price_id.integer' => ':attribute phải là số nguyên',
            'price_id.exists' => ':attribute không tồn tại',
            'voucher_id.integer' => ':attribute phải là số nguyên',
            'voucher_id.exists' => ':attribute không tồn tại',
        ];
    }

    public function attributes(): array
    {
        return [
            'name_booker' => 'Tên người đặt',
            'email_booker' => 'Email người đặt',
            'phone_booker' => 'Số điện thoại người đặt',
            'number_people' => 'Số người',
            'note' => 'Ghi chú',
            'date' => 'Ngày đặt',
            'time_id' => 'Check-in',
            'service_id' => 'Dịch vụ',
            'price_id' => 'Giá dịch vụ',
            'customer_id' => 'Khách hàng',
            'voucher_id' => 'Voucher',
        ];
    }
}
