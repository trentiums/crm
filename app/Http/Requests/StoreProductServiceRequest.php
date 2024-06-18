<?php

namespace App\Http\Requests;

use App\Models\ProductService;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductServiceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_service_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'documents' => [
                'file',
                'max:'.config('settings.file_size.general'),
                'mimes:'.config('settings.supported_file_extension.general'),
            ]
        ];
    }
}
