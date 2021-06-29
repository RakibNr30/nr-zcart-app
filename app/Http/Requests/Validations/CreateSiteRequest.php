<?php

namespace App\Http\Requests\Validations;

use App\Http\Requests\Request;

class CreateSiteRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'country' => 'required',
           'code' => 'required|max:3|unique:sites',
           'order' => 'nullable|numeric'
        ];
    }
}