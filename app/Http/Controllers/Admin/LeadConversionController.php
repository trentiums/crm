<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLeadConversionRequest;
use App\Http\Requests\StoreLeadConversionRequest;
use App\Http\Requests\UpdateLeadConversionRequest;
use App\Models\LeadConversion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LeadConversionController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('lead_conversion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LeadConversion::query()->select(sprintf('%s.*', (new LeadConversion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_conversion_show';
                $editGate      = 'lead_conversion_edit';
                $deleteGate    = 'lead_conversion_delete';
                $crudRoutePart = 'lead-conversions';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.leadConversions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('lead_conversion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.leadConversions.create');
    }

    public function store(StoreLeadConversionRequest $request)
    {
        $leadConversion = LeadConversion::create($request->all());

        return redirect()->route('admin.lead-conversions.index');
    }

    public function edit(LeadConversion $leadConversion)
    {
        abort_if(Gate::denies('lead_conversion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.leadConversions.edit', compact('leadConversion'));
    }

    public function update(UpdateLeadConversionRequest $request, LeadConversion $leadConversion)
    {
        $leadConversion->update($request->all());

        return redirect()->route('admin.lead-conversions.index');
    }

    public function show(LeadConversion $leadConversion)
    {
        abort_if(Gate::denies('lead_conversion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.leadConversions.show', compact('leadConversion'));
    }

    public function destroy(LeadConversion $leadConversion)
    {
        abort_if(Gate::denies('lead_conversion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leadConversion->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeadConversionRequest $request)
    {
        $leadConversions = LeadConversion::find(request('ids'));

        foreach ($leadConversions as $leadConversion) {
            $leadConversion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
