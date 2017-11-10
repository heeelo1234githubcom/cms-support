<?php

namespace App\Http\Requests;

class PromotionFormRequest extends BaseRequest
{
    private $attributeLabels = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $promotionId = $this->input('promotion_id');

        $rules = [
            'title' => 'required|max:190',
            'slug' => 'required|max:190|unique:promotions,slug,' . $promotionId . ',promotion_id',
            'status' => 'required',
            'content' => 'required'
        ];

        if ($this->input('start_date')) {
            $rules['start_date'] = 'date_format:d/m/Y';
        }

        if ($this->input('end_date')) {
            $rules['end_date'] = 'date_format:d/m/Y';
        }

        if ($this->input('start_date') && $this->input('end_date')) {
            $rules['start_date'] .= '|before_or_equal:end_date';
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
        $title = $this->getAttributeLabel('backend.promotion.title');
        $slug = $this->getAttributeLabel('backend.promotion.slug');
        $content = $this->getAttributeLabel('backend.promotion.content');
        $status = $this->getAttributeLabel('backend.promotion.labelStatus');
        $startDate = $this->getAttributeLabel('backend.promotion.start_date');
        $endDate = $this->getAttributeLabel('backend.promotion.end_date');

        return [
            'title.required' => trans('validation.required', ['attribute' => $title]),
            'title.max' => trans('validation.max.string', ['attribute' => $title]),
            'slug.required' => trans('validation.required', ['attribute' => $slug]),
            'slug.max' => trans('validation.max.string', ['attribute' => $slug]),
            'slug.unique' => trans('validation.unique', ['attribute' => $slug]),
            'status.required' => trans('validation.required', ['attribute' => $status]),
            'content.required' => trans('validation.required', ['attribute' => $content]),
            'start_date.date_format' => trans('validation.date_format', ['attribute' => $startDate]),
            'start_date.before_or_equal' => $startDate . ' phải trước hoặc bằng ngày kết thúc.',
            'end_date.date_format' => trans('validation.date_format', ['attribute' => $endDate]),
        ];
    }
}
