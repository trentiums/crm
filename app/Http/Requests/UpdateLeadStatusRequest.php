<?php

namespace App\Http\Requests;

use App\Models\LeadStatus;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLeadStatusRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lead_status_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:lead_statuses,name,' . request()->route('lead_status')->id,
            ],
        ];
    }
}
