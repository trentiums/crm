<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLeadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lead_edit');
    }

    public function rules()
    {
        return [
            'company_user_id' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'name' => [
                'string',
                'required',
            ],
            'phone' => [
                'string',
                'nullable',
            ],
            'company_name' => [
                'string',
                'nullable',
            ],
            'company_size' => [
                'string',
                'nullable',
            ],
            'company_website' => [
                'string',
                'nullable',
            ],
            'lead_status_id' => [
                'required',
                'integer',
            ],
            'lead_channel_id' => [
                'required',
                'integer',
            ],
            'product_services.*' => [
                'integer',
            ],
            'product_services' => [
                'required',
                'array',
            ],
            'lead_conversion_id' => [
                'required',
                'integer',
            ],
            'time_line' => [
                'string',
                'nullable',
            ],
            'win_close_reason' => [
                'string',
                'nullable',
            ],
            'deal_close_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
