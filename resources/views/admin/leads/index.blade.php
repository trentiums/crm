@extends('layouts.admin')
@section('content')
@can('lead_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.leads.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.lead.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Lead', 'route' => 'admin.leads.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.lead.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Lead">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.company_user_id') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.phone') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.company_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.company_size') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.company_website') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.lead_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.lead_channel') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.product_service') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.lead_conversion') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.budget') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.time_line') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.description') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.deal_amount') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.win_close_reason') }}
                    </th>
                    <th>
                        {{ trans('cruds.lead.fields.deal_close_date') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('lead_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.leads.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.leads.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'company_user_id', name: 'company_user_id' },
{ data: 'name', name: 'name' },
{ data: 'email', name: 'email' },
{ data: 'phone', name: 'phone' },
{ data: 'company_name', name: 'company_name' },
{ data: 'company_size', name: 'company_size' },
{ data: 'company_website', name: 'company_website' },
{ data: 'lead_status_name', name: 'lead_status.name' },
{ data: 'lead_channel_name', name: 'lead_channel.name' },
{ data: 'product_service', name: 'product_services.name' },
{ data: 'lead_conversion_name', name: 'lead_conversion.name' },
{ data: 'budget', name: 'budget' },
{ data: 'time_line', name: 'time_line' },
{ data: 'description', name: 'description' },
{ data: 'deal_amount', name: 'deal_amount' },
{ data: 'win_close_reason', name: 'win_close_reason' },
{ data: 'deal_close_date', name: 'deal_close_date' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  };
  let table = $('.datatable-Lead').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

});

</script>
@endsection
