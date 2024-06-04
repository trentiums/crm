@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.lead.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.leads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.id') }}
                        </th>
                        <td>
                            {{ $lead->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.company_user_id') }}
                        </th>
                        <td>
                            {{ $lead->company_user_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.name') }}
                        </th>
                        <td>
                            {{ $lead->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.email') }}
                        </th>
                        <td>
                            {{ $lead->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.phone') }}
                        </th>
                        <td>
                            {{ $lead->phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.company_name') }}
                        </th>
                        <td>
                            {{ $lead->company_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.company_size') }}
                        </th>
                        <td>
                            {{ $lead->company_size }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.company_website') }}
                        </th>
                        <td>
                            {{ $lead->company_website }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.lead_status') }}
                        </th>
                        <td>
                            {{ $lead->lead_status->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.lead_channel') }}
                        </th>
                        <td>
                            {{ $lead->lead_channel->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.product_service') }}
                        </th>
                        <td>
                            @foreach($lead->product_services as $key => $product_service)
                                <span class="label label-info">{{ $product_service->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.lead_conversion') }}
                        </th>
                        <td>
                            {{ $lead->lead_conversion->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.budget') }}
                        </th>
                        <td>
                            {{ $lead->budget }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.time_line') }}
                        </th>
                        <td>
                            {{ $lead->time_line }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.description') }}
                        </th>
                        <td>
                            {{ $lead->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.deal_amount') }}
                        </th>
                        <td>
                            {{ $lead->deal_amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.win_close_reason') }}
                        </th>
                        <td>
                            {{ $lead->win_close_reason }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.deal_close_date') }}
                        </th>
                        <td>
                            {{ $lead->deal_close_date }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.leads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
