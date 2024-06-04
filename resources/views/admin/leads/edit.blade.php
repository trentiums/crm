@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.lead.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.leads.update", [$lead->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="company_user_id">{{ trans('cruds.lead.fields.company_user_id') }}</label>
                <input class="form-control {{ $errors->has('company_user_id') ? 'is-invalid' : '' }}" type="number" name="company_user_id" id="company_user_id" value="{{ old('company_user_id', $lead->company_user_id) }}" step="1" required>
                @if($errors->has('company_user_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('company_user_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.company_user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.lead.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $lead->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.lead.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $lead->email) }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phone">{{ trans('cruds.lead.fields.phone') }}</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', $lead->phone) }}">
                @if($errors->has('phone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.phone_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="company_name">{{ trans('cruds.lead.fields.company_name') }}</label>
                <input class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}" type="text" name="company_name" id="company_name" value="{{ old('company_name', $lead->company_name) }}">
                @if($errors->has('company_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('company_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.company_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="company_size">{{ trans('cruds.lead.fields.company_size') }}</label>
                <input class="form-control {{ $errors->has('company_size') ? 'is-invalid' : '' }}" type="text" name="company_size" id="company_size" value="{{ old('company_size', $lead->company_size) }}">
                @if($errors->has('company_size'))
                    <div class="invalid-feedback">
                        {{ $errors->first('company_size') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.company_size_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="company_website">{{ trans('cruds.lead.fields.company_website') }}</label>
                <input class="form-control {{ $errors->has('company_website') ? 'is-invalid' : '' }}" type="text" name="company_website" id="company_website" value="{{ old('company_website', $lead->company_website) }}">
                @if($errors->has('company_website'))
                    <div class="invalid-feedback">
                        {{ $errors->first('company_website') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.company_website_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="lead_status_id">{{ trans('cruds.lead.fields.lead_status') }}</label>
                <select class="form-control select2 {{ $errors->has('lead_status') ? 'is-invalid' : '' }}" name="lead_status_id" id="lead_status_id" required>
                    @foreach($lead_statuses as $id => $entry)
                        <option value="{{ $id }}" {{ (old('lead_status_id') ? old('lead_status_id') : $lead->lead_status->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('lead_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lead_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.lead_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="lead_channel_id">{{ trans('cruds.lead.fields.lead_channel') }}</label>
                <select class="form-control select2 {{ $errors->has('lead_channel') ? 'is-invalid' : '' }}" name="lead_channel_id" id="lead_channel_id" required>
                    @foreach($lead_channels as $id => $entry)
                        <option value="{{ $id }}" {{ (old('lead_channel_id') ? old('lead_channel_id') : $lead->lead_channel->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('lead_channel'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lead_channel') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.lead_channel_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="product_services">{{ trans('cruds.lead.fields.product_service') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('product_services') ? 'is-invalid' : '' }}" name="product_services[]" id="product_services" multiple required>
                    @foreach($product_services as $id => $product_service)
                        <option value="{{ $id }}" {{ (in_array($id, old('product_services', [])) || $lead->product_services->contains($id)) ? 'selected' : '' }}>{{ $product_service }}</option>
                    @endforeach
                </select>
                @if($errors->has('product_services'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_services') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.product_service_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="lead_conversion_id">{{ trans('cruds.lead.fields.lead_conversion') }}</label>
                <select class="form-control select2 {{ $errors->has('lead_conversion') ? 'is-invalid' : '' }}" name="lead_conversion_id" id="lead_conversion_id" required>
                    @foreach($lead_conversions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('lead_conversion_id') ? old('lead_conversion_id') : $lead->lead_conversion->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('lead_conversion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lead_conversion') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.lead_conversion_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="budget">{{ trans('cruds.lead.fields.budget') }}</label>
                <input class="form-control {{ $errors->has('budget') ? 'is-invalid' : '' }}" type="number" name="budget" id="budget" value="{{ old('budget', $lead->budget) }}" step="0.01">
                @if($errors->has('budget'))
                    <div class="invalid-feedback">
                        {{ $errors->first('budget') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.budget_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="time_line">{{ trans('cruds.lead.fields.time_line') }}</label>
                <input class="form-control {{ $errors->has('time_line') ? 'is-invalid' : '' }}" type="text" name="time_line" id="time_line" value="{{ old('time_line', $lead->time_line) }}">
                @if($errors->has('time_line'))
                    <div class="invalid-feedback">
                        {{ $errors->first('time_line') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.time_line_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.lead.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $lead->description) }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deal_amount">{{ trans('cruds.lead.fields.deal_amount') }}</label>
                <input class="form-control {{ $errors->has('deal_amount') ? 'is-invalid' : '' }}" type="number" name="deal_amount" id="deal_amount" value="{{ old('deal_amount', $lead->deal_amount) }}" step="0.01">
                @if($errors->has('deal_amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deal_amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.deal_amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="win_close_reason">{{ trans('cruds.lead.fields.win_close_reason') }}</label>
                <input class="form-control {{ $errors->has('win_close_reason') ? 'is-invalid' : '' }}" type="text" name="win_close_reason" id="win_close_reason" value="{{ old('win_close_reason', $lead->win_close_reason) }}">
                @if($errors->has('win_close_reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('win_close_reason') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.win_close_reason_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deal_close_date">{{ trans('cruds.lead.fields.deal_close_date') }}</label>
                <input class="form-control date {{ $errors->has('deal_close_date') ? 'is-invalid' : '' }}" type="text" name="deal_close_date" id="deal_close_date" value="{{ old('deal_close_date', $lead->deal_close_date) }}">
                @if($errors->has('deal_close_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deal_close_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.deal_close_date_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
