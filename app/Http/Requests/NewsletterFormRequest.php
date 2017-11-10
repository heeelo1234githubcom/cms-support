<?php

namespace App\Http\Requests;

class NewsletterFormRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules = [
            'newsletter_email' => 'required|email|max:150',
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $email = 'Email';

        return [
            'newsletter_email.max' => trans('validation.max.string', ['attribute' => $email]),
            'newsletter_email.required' => trans('validation.required', ['attribute' => $email]),
            'newsletter_email.email' => 'Định dạng email không chính xác.',
        ];
    }
}
