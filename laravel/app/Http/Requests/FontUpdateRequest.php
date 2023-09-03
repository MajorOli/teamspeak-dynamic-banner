<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FontUpdateRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'font_id' => $this->route('font_id'),
            'filename' => $this->file->getClientOriginalName(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'font_id' => ['required', 'integer', 'exists:App\Models\Font,id'],
            'file' => ['required', 'file', 'mimetypes:font/ttf,font/sfnt'],
            'filename' => ['unique:fonts,filename,'.$this->font_id],
        ];
    }
}
