<?php

namespace App\Http\Requests;

class PhotoFormRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $mediaId = $this->input('media_id');

        $rules = [
            'title' => 'required|max:190',
            'album_id' => 'required',
            'status' => 'required'
        ];

        $rules['file'] = '';
        if ( !$mediaId) {
            $rules['file'] = 'required|';
        }

        $rules['file'] .= 'bail|mimes:jpeg,jpg,png|max:5120|file';

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
        $title = $this->getAttributeLabel('backend.mediaPhoto.title');
        $status = $this->getAttributeLabel('backend.mediaPhoto.status');
        $file = $this->getAttributeLabel('backend.mediaPhoto.file');
        $album = $this->getAttributeLabel('backend.mediaPhoto.album');

        return [
            'title.required' => trans('validation.required', ['attribute' => $title]),
            'title.max' => trans('validation.max.string', ['attribute' => $title]),
            'album_id.required' => trans('validation.required', ['attribute' => $album]),
            'status.required' => trans('validation.required', ['attribute' => $status]),
            'file.required' => trans('validation.required', ['attribute' => $file]),
            'file.mimes' => trans('validation.mimes', ['attribute' => $file]),
            'file.max' => trans('validation.max.file', ['attribute' => $file]),
        ];
    }
}
