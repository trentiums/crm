@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.productService.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-services.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.productService.fields.id') }}
                        </th>
                        <td>
                            {{ $productService->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productService.fields.name') }}
                        </th>
                        <td>
                            {{ $productService->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productService.fields.description') }}
                        </th>
                        <td>
                            {{ $productService->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.productService.fields.documents') }}
                        </th>
                        <td>
                            @if($productService->documents)
                                <a href="{{ $productService->documents->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.product-services.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
