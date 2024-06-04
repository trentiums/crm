<?php

namespace App\Http\Requests;

use App\Models\LeadChannel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLeadChannelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lead_channel_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:lead_channels',
            ],
        ];
    }
}
