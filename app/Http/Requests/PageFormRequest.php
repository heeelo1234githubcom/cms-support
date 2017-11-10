<?php

namespace App\Http\Requests;

class PageFormRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $pageId = $this->input('page_id');

        $rules = [
            'title' => 'required|max:190',
            'slug' => 'required|max:190|unique:pages,slug,' . $pageId . ',page_id',
            'status' => 'required',
            'content' => 'required'
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
        $title = $this->getAttributeLabel('backend.pageEdit.labelTitle');
        $slug = $this->getAttributeLabel('backend.pageEdit.labelSlug');
        $status = $this->getAttributeLabel('backend.pageEdit.labelStatus');
        $content = $this->getAttributeLabel('backend.pageEdit.labelContent');

        return [
            'title.required' => trans('validation.required', ['attribute' => $title]),
            'title.max' => trans('validation.max.string', ['attribute' => $title]),
            'slug.required' => trans('validation.required', ['attribute' => $slug]),
            'slug.max' => trans('validation.max.string', ['attribute' => $slug]),
            'slug.unique' => trans('validation.unique', ['attribute' => $slug]),
            'status.required' => trans('validation.required', ['attribute' => $status]),
            'content.required' => trans('validation.required', ['attribute' => $content]),
        ];
    }
}
