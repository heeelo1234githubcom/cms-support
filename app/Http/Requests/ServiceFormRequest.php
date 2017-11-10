<?php

namespace App\Http\Requests;

class ServiceFormRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $serviceId = $this->input('service_id');

        $rules = [
            'title' => 'required|max:190',
            'slug' => 'required|max:190|unique:services,slug,' . $serviceId . ',service_id',
            'status' => 'required',
            'content' => 'required',
            'show_at_home' => 'required',
            //'cover' => 'bail|mimes:jpeg,jpg,png|max:5120|file'
        ];

        $coverRule = '';
        if ( !$serviceId) {
            $coverRule .= 'required';
        }

        $coverRule .= '|bail|mimes:jpeg,jpg,png|max:5120|file';

        $rules['cover'] = $coverRule;

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
        $title = $this->getAttributeLabel('backend.service.add.labelTitle');
        $slug = $this->getAttributeLabel('backend.service.add.labelSlug');
        $cover = $this->getAttributeLabel('backend.service.add.labelCover');
        $status = $this->getAttributeLabel('backend.service.add.labelStatus');
        $content = $this->getAttributeLabel('backend.service.add.labelContent');
        $showAtHome = 'Hiển thị tại trang chủ';

        return [
            'title.required' => trans('validation.required', ['attribute' => $title]),
            'title.max' => trans('validation.max.string', ['attribute' => $title]),
            'slug.required' => trans('validation.required', ['attribute' => $slug]),
            'slug.max' => trans('validation.max.string', ['attribute' => $slug]),
            'slug.unique' => trans('validation.unique', ['attribute' => $slug]),
            'cover.mimes' => trans('validation.mimes', ['attribute' => $cover]),
            'cover.max' => trans('validation.max.file', ['attribute' => $cover]),
            'cover.required' => trans('validation.required', ['attribute' => $cover]),
            'status.required' => trans('validation.required', ['attribute' => $status]),
            'content.required' => trans('validation.required', ['attribute' => $content]),
            'show_at_home.required' => trans('validation.required', ['attribute' => $showAtHome]),
        ];
    }
}
