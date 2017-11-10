<?php

namespace App\Http\Requests;

class UpdateConfigRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules = [
            'web_title' => 'required|max:190',
            'hotline_number' => 'required',
            'web_description' => 'required|max:200',
            'web_keyword' => 'required|max:200',
            'contact_info' => 'required',
            'website_cover' => 'bail|mimes:jpeg,jpg,png|max:5120|file'
        ];

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
        $title = $this->getAttributeLabel('backend.settings.webTitle');
        $description = $this->getAttributeLabel('backend.settings.description');
        $keywords = $this->getAttributeLabel('backend.settings.keywords');
        $info = $this->getAttributeLabel('backend.settings.contactInfo');
        $hotLine = $this->getAttributeLabel('backend.settings.hotline');
        $cover = $this->getAttributeLabel('backend.settings.cover');

        return [
            'web_title.required' => trans('validation.required', ['attribute' => $title]),
            'web_title.max' => trans('validation.max.string', ['attribute' => $title]),
            'hotline_number.required' => trans('validation.required', ['attribute' => $hotLine]),
            'web_description.required' => trans('validation.required', ['attribute' => $description]),
            'web_description.max' => trans('validation.max.string', ['attribute' => $description]),
            'web_keyword.required' => trans('validation.required', ['attribute' => $keywords]),
            'web_keyword.max' => trans('validation.max.string', ['attribute' => $keywords]),
            'contact_info.required' => trans('validation.required', ['attribute' => $info]),
            'website_cover.mimes' => trans('validation.mimes', ['attribute' => $cover]),
            'website_cover.max' => trans('validation.max.file', ['attribute' => $cover]),
        ];
    }
}
