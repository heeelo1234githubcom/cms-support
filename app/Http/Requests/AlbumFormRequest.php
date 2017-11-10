<?php

namespace App\Http\Requests;

class AlbumFormRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $albumId = $this->input('album_id');

        $rules = [
            'title' => 'required|max:190',
            'slug' => 'required|max:190|unique:albums,slug,' . $albumId . ',album_id',
            'status' => 'required'
        ];

        if ( !$albumId) {
            $rules['type'] = 'required';
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
        $title = $this->getAttributeLabel('backend.mediaAlbum.title');
        $slug = $this->getAttributeLabel('backend.mediaAlbum.slug');
        $status = $this->getAttributeLabel('backend.mediaAlbum.status');
        $type = $this->getAttributeLabel('backend.mediaAlbum.type');

        return [
            'title.required' => trans('validation.required', ['attribute' => $title]),
            'title.max' => trans('validation.max.string', ['attribute' => $title]),
            'slug.required' => trans('validation.required', ['attribute' => $slug]),
            'slug.max' => trans('validation.max.string', ['attribute' => $slug]),
            'slug.unique' => trans('validation.unique', ['attribute' => $slug]),
            'status.required' => trans('validation.required', ['attribute' => $status]),
            'type.required' => trans('validation.required', ['attribute' => $type]),
        ];
    }
}
