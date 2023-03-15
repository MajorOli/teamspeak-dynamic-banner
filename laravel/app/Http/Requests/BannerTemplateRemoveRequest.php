<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerTemplateRemoveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'banner_id' => ['required', 'integer', 'exists:App\Models\Banner,id'],
            'template_id' => ['required', 'integer', 'exists:App\Models\Template,id'],
        ];
    }
}
