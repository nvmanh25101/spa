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
            "name_contact" => [
                'required',
                'string',
                'max:50',
            ],
            "email_contact" => [
                'required',
                'string',
                'max:50',
                'email'
            ],
            "phone_contact" => [
                'required',
                'string',
                'max:15',
            ],
            "number_people" => [
                'required',
                'integer',
                'min:1'
            ],
            'departure_date' => [
                'required',
                'date_format:d-m-Y',
                'after_or_equal:today',
            ],
            'voucher_id' => [
                'nullable',
                'integer',
                'exists:vouchers,id',
            ],
            'tour_id' => [
                'required',
                'integer',
                'exists:tours,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name_contact.required' => 'Vui lòng nhập :attribute',
            'name_contact.string' => ':attribute phải là chuỗi',
            'name_contact.max' => ':attribute không được vượt quá :max ký tự',
            'email_contact.required' => 'Vui lòng nhập :attribute',
            'email_contact.string' => ':attribute phải là chuỗi',
            'email_contact.max' => ':attribute không được vượt quá :max ký tự',
            'email_contact.email' => ':attribute không đúng định dạng',
            'phone_contact.required' => 'Vui lòng nhập :attribute',
            'phone_contact.string' => ':attribute phải là chuỗi',
            'phone_contact.max' => ':attribute không được vượt quá :max ký tự',
            'number_people.required' => 'Vui lòng nhập :attribute',
            'number_people.integer' => ':attribute phải là số nguyên',
            'number_people.min' => ':attribute phải lớn hơn hoặc bằng :min',
            'departure_date.required' => 'Vui lòng nhập :attribute',
            'departure_date.departure_date_format' => ':attribute không đúng định dạng dd-mm-yyyy',
            'departure_date.after_or_equal' => ':attribute phải lớn hơn hoặc bằng ngày hôm nay',
            'voucher_id.integer' => ':attribute phải là số nguyên',
            'voucher_id.exists' => ':attribute không tồn tại',
        ];
    }

    public function attributes(): array
    {
        return [
            'name_contact' => 'Tên người liên hệ',
            'email_contact' => 'Email liên hệ',
            'phone_contact' => 'Số điện thoại liên hệ',
            'number_people' => 'Số người',
            'departure_date' => 'Ngày khởi hành',
            'voucher_id' => 'Voucher',
        ];
    }
}
