<?php

namespace App\Http\Requests;

use App\Models\LeadConversion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLeadConversionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lead_conversion_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:lead_conversions,name,' . request()->route('lead_conversion')->id,
            ],
        ];
    }
}
