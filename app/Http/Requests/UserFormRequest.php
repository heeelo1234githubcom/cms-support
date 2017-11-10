<?php

namespace App\Http\Requests;

class UserFormRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = $this->input('user_id');

        $rules = [
            'name' => 'required|max:150'
        ];

        $password = $this->input('password');
        $confirmPassword = $this->input('confirmPassword');

        $passwordRule = [];
        $confirmPasswordRule = [];
        $emailRule = [];

        /* required on create */
        if ( !$userId) {
            $passwordRule[] = 'required';
            $confirmPasswordRule[] = 'required';
            $emailRule[] = 'required';
        }

        if ($confirmPassword) {
            $passwordRule[] = 'required';
        }

        if ($password) {
            $confirmPasswordRule[] = 'required';

            if ($password != $confirmPassword) {
                $confirmPasswordRule[] = 'confirmed';
            }

            $passwordRule[] = 'min:6';
            $passwordRule[] = 'max:12';
        }

        $emailRule[] = 'email|max:190|unique:users,email,' . $userId . ',user_id';

        $rules['email'] = implode('|', $emailRule);
        $rules['password'] = implode('|', $passwordRule);
        $rules['password'] = implode('|', $passwordRule);
        $rules['confirmPassword'] = implode('|', $confirmPasswordRule);

        //dd($rules);

        return $rules;
    }

    /***
     * @param $attribute
     * @return mixed
     */
    private function getAttributeLabel($attribute)
    {
        if ( !isset($this->attributeLabels[$attribute])) {
            $this->attributeLabels[$attribute] = trans($attribute);
        }

        return $this->attributeLabels[$attribute];
    }

    /**
     * @return array
     */
    public function messages()
    {
        $name = $this->getAttributeLabel('backend.user.name');
        $email = $this->getAttributeLabel('backend.user.email');
        $password = $this->getAttributeLabel('backend.user.password');
        $confirmPassword = $this->getAttributeLabel('backend.user.password_confirm');

        return [
            'name.required' => trans('validation.required', ['attribute' => $name]),
            'name.max' => trans('validation.max.string', ['attribute' => $name]),
            'email.required' => trans('validation.required', ['attribute' => $email]),
            'email.email' => trans('validation.email', ['attribute' => $email]),
            'email.unique' => trans('validation.unique', ['attribute' => $email]),
            'password.required' => trans('validation.required', ['attribute' => $password]),
            'password.min' => trans('validation.min.string', ['attribute' => $password]),
            'password.max' => trans('validation.max.string', ['attribute' => $password]),
            'confirmPassword.required' => trans('validation.required', ['attribute' => $confirmPassword]),
            'confirmPassword.confirmed' => trans('validation.invalid_confirm_password'),
        ];
    }
}
