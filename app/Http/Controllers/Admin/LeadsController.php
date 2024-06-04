<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLeadRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\LeadChannel;
use App\Models\LeadConversion;
use App\Models\LeadStatus;
use App\Models\ProductService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LeadsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('lead_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Lead::with(['lead_status', 'lead_channel', 'product_services', 'lead_conversion'])->select(sprintf('%s.*', (new Lead)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_show';
                $editGate      = 'lead_edit';
                $deleteGate    = 'lead_delete';
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('company_user_id', function ($row) {
                return $row->company_user_id ? $row->company_user_id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : '';
            });
            $table->editColumn('company_name', function ($row) {
                return $row->company_name ? $row->company_name : '';
            });
            $table->editColumn('company_size', function ($row) {
                return $row->company_size ? $row->company_size : '';
            });
            $table->editColumn('company_website', function ($row) {
                return $row->company_website ? $row->company_website : '';
            });
            $table->addColumn('lead_status_name', function ($row) {
                return $row->lead_status ? $row->lead_status->name : '';
            });

            $table->addColumn('lead_channel_name', function ($row) {
                return $row->lead_channel ? $row->lead_channel->name : '';
            });

            $table->editColumn('product_service', function ($row) {
                $labels = [];
                foreach ($row->product_services as $product_service) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $product_service->name);
                }

                return implode(' ', $labels);
            });
            $table->addColumn('lead_conversion_name', function ($row) {
                return $row->lead_conversion ? $row->lead_conversion->name : '';
            });

            $table->editColumn('budget', function ($row) {
                return $row->budget ? $row->budget : '';
            });
            $table->editColumn('time_line', function ($row) {
                return $row->time_line ? $row->time_line : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('deal_amount', function ($row) {
                return $row->deal_amount ? $row->deal_amount : '';
            });
            $table->editColumn('win_close_reason', function ($row) {
                return $row->win_close_reason ? $row->win_close_reason : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'lead_status', 'lead_channel', 'product_service', 'lead_conversion']);

            return $table->make(true);
        }

        return view('admin.leads.index');
    }

    public function create()
    {
        abort_if(Gate::denies('lead_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lead_statuses = LeadStatus::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lead_channels = LeadChannel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product_services = ProductService::pluck('name', 'id');

        $lead_conversions = LeadConversion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.leads.create', compact('lead_channels', 'lead_conversions', 'lead_statuses', 'product_services'));
    }

    public function store(StoreLeadRequest $request)
    {
        $lead = Lead::create($request->all());
        $lead->product_services()->sync($request->input('product_services', []));

        return redirect()->route('admin.leads.index');
    }

    public function edit(Lead $lead)
    {
        abort_if(Gate::denies('lead_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lead_statuses = LeadStatus::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lead_channels = LeadChannel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product_services = ProductService::pluck('name', 'id');

        $lead_conversions = LeadConversion::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lead->load('lead_status', 'lead_channel', 'product_services', 'lead_conversion');

        return view('admin.leads.edit', compact('lead', 'lead_channels', 'lead_conversions', 'lead_statuses', 'product_services'));
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $lead->update($request->all());
        $lead->product_services()->sync($request->input('product_services', []));

        return redirect()->route('admin.leads.index');
    }

    public function show(Lead $lead)
    {
        abort_if(Gate::denies('lead_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lead->load('lead_status', 'lead_channel', 'product_services', 'lead_conversion');

        return view('admin.leads.show', compact('lead'));
    }

    public function destroy(Lead $lead)
    {
        abort_if(Gate::denies('lead_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lead->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeadRequest $request)
    {
        $leads = Lead::find(request('ids'));

        foreach ($leads as $lead) {
            $lead->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
