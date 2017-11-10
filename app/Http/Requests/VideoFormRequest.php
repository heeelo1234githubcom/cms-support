<?php

namespace App\Http\Requests;

class VideoFormRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|max:190',
            'album_id' => 'required',
            'status' => 'required',
            'file' => 'required|validateVideoId',
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
        $title = $this->getAttributeLabel('backend.mediaVideo.title');
        $status = $this->getAttributeLabel('backend.mediaVideo.status');
        $file = $this->getAttributeLabel('backend.mediaVideo.file');
        $album = $this->getAttributeLabel('backend.mediaVideo.album');

        return [
            'title.required' => trans('validation.required', ['attribute' => $title]),
            'title.max' => trans('validation.max.string', ['attribute' => $title]),
            'album_id.required' => trans('validation.required', ['attribute' => $album]),
            'status.required' => trans('validation.required', ['attribute' => $status]),
            'file.required' => trans('validation.required', ['attribute' => $file]),
            'file.url' => trans('validation.active_url', ['attribute' => $file])
        ];
    }
}
