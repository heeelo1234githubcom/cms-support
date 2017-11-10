<?php

namespace App\Http\Requests;

class ContactFormRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules = [
            'contact_name' => 'required|max:190',
            'contact_email' => 'required|email|max:150',
            //'contact_phone' => 'required',
            'contact_content' => 'required'
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $name = 'Tên của bạn';
        $phone = 'Số điện thoại';
        $email = 'Email';
        $content = 'Nội dung liên hệ';

        return [
            'contact_name.required' => trans('validation.required', ['attribute' => $name]),
            'contact_name.max' => trans('validation.max.string', ['attribute' => $name]),
            'contact_email.required' => trans('validation.required', ['attribute' => $email]),
            'contact_email.email' => 'Định dạng email không chính xác.',
            'contact_email.max' => trans('validation.max.string', ['attribute' => $email]),
            'contact_phone.required' => trans('validation.required', ['attribute' => $phone]),
            'contact_content.required' => trans('validation.required', ['attribute' => $content]),
        ];
    }
}
