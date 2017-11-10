<?php

namespace App\Http\Requests;

class MenuFormRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $menuId = $this->input('menu_id');
        $rules = [
            'title' => 'required|max:190',
            'status' => 'required',
        ];

        if ( !$menuId) {
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
        $title = $this->getAttributeLabel('backend.menu.title');
        $status = $this->getAttributeLabel('backend.menu.status');
        $type = 'Loại menu';

        return [
            'title.required' => trans('validation.required', ['attribute' => $title]),
            'title.max' => trans('validation.max.string', ['attribute' => $title]),
            'status.required' => trans('validation.required', ['attribute' => $status]),
            'type.required' => trans('validation.required', ['attribute' => $type]),
        ];
    }
}
