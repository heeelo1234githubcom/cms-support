<?php

namespace App\Http\Requests;

class UpdateProfileRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:150',
            'avatar' => 'bail|mimes:jpeg,jpg,png|max:5120|file'
        ];

        $oldPassword = $this->input('oldPassword');
        $newPassword = $this->input('newPassword');
        $confirmPassword = $this->input('confirmPassword');

        $oldPasswordRule = [];
        $newPasswordRule = [];
        $confirmPasswordRule = [];

        if ($oldPassword) {
            $oldPasswordRule[] = 'validateOldPassword';
            $newPasswordRule[] = 'required_unless:oldPassword,';
            $confirmPasswordRule[] = 'required_unless:oldPassword,';

            if ($newPassword != $confirmPassword) {
                $confirmPasswordRule[] = 'confirmed';
            }
        }

        if ($newPassword) {
            $oldPasswordRule[] = 'required_unless:newPassword,';
            $confirmPasswordRule[] = 'required_unless:newPassword,';
        }

        if ($confirmPassword) {
            $oldPasswordRule[] = 'required_unless:confirmPassword,';
            $newPasswordRule[] = 'required_unless:confirmPassword,';
        }

        if ($newPasswordRule) {
            $newPasswordRule[] = 'min:6';
            $newPasswordRule[] = 'max:12';
            $rules['newPassword'] = implode('|', $newPasswordRule);
        }

        if ($oldPasswordRule) {
            $rules['oldPassword'] = implode('|', $oldPasswordRule);
        }

        if ($confirmPasswordRule) {
            $rules['confirmPassword'] = implode('|', $confirmPasswordRule);
        }

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
        $name = $this->getAttributeLabel('backend.profile.name');
        $avatar = $this->getAttributeLabel('backend.profile.avatar');
        $oldPassword = $this->getAttributeLabel('backend.profile.oldPassword');
        $newPassword = $this->getAttributeLabel('backend.profile.newPassword');
        $confirmPassword = $this->getAttributeLabel('backend.profile.confirmPassword');

        return [
            'name.required' => trans('validation.required', ['attribute' => $name]),
            'name.max' => trans('validation.max.string', ['attribute' => $name]),
            'avatar.mimes' => trans('validation.mimes', ['attribute' => $avatar]),
            'avatar.max' => trans('validation.max.file', ['attribute' => $avatar]),
            'oldPassword.required_unless' => trans('validation.required', ['attribute' => $oldPassword]),
            'newPassword.required_unless' => trans('validation.required', ['attribute' => $newPassword]),
            'newPassword.min' => trans('validation.min.string', ['attribute' => $newPassword]),
            'newPassword.max' => trans('validation.max.string', ['attribute' => $newPassword]),
            'confirmPassword.required_unless' => trans('validation.required', ['attribute' => $confirmPassword]),
            'confirmPassword.confirmed' => trans('validation.invalid_confirm_password'),
        ];
    }
}
