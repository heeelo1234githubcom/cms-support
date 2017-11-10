<?php

namespace App\Http\Requests;

class SlideFormRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $slideId = $this->input('slide_id');

        $rules = [
            'title' => 'required|max:190',
            'status' => 'required'
        ];

        $rules['path'] = '';
        if ( !$slideId) {
            $rules['path'] = 'required|';
        }

        $rules['path'] .= 'bail|mimes:jpeg,jpg,png|max:5120|file';

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
        $title = $this->getAttributeLabel('backend.slide.title');
        $status = $this->getAttributeLabel('backend.slide.status');
        $file = $this->getAttributeLabel('backend.slide.file');

        return [
            'title.required' => trans('validation.required', ['attribute' => $title]),
            'title.max' => trans('validation.max.string', ['attribute' => $title]),
            'status.required' => trans('validation.required', ['attribute' => $status]),
            'path.required' => trans('validation.required', ['attribute' => $file]),
            'path.mimes' => trans('validation.mimes', ['attribute' => $file]),
            'path.max' => trans('validation.max.file', ['attribute' => $file]),
        ];
    }
}
