<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyLeadChannelRequest;
use App\Http\Requests\StoreLeadChannelRequest;
use App\Http\Requests\UpdateLeadChannelRequest;
use App\Models\LeadChannel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LeadChannelsController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('lead_channel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LeadChannel::query()->select(sprintf('%s.*', (new LeadChannel)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'lead_channel_show';
                $editGate      = 'lead_channel_edit';
                $deleteGate    = 'lead_channel_delete';
                $crudRoutePart = 'lead-channels';

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

        return view('admin.leadChannels.index');
    }

    public function create()
    {
        abort_if(Gate::denies('lead_channel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.leadChannels.create');
    }

    public function store(StoreLeadChannelRequest $request)
    {
        $leadChannel = LeadChannel::create($request->all());

        return redirect()->route('admin.lead-channels.index');
    }

    public function edit(LeadChannel $leadChannel)
    {
        abort_if(Gate::denies('lead_channel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.leadChannels.edit', compact('leadChannel'));
    }

    public function update(UpdateLeadChannelRequest $request, LeadChannel $leadChannel)
    {
        $leadChannel->update($request->all());

        return redirect()->route('admin.lead-channels.index');
    }

    public function show(LeadChannel $leadChannel)
    {
        abort_if(Gate::denies('lead_channel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.leadChannels.show', compact('leadChannel'));
    }

    public function destroy(LeadChannel $leadChannel)
    {
        abort_if(Gate::denies('lead_channel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leadChannel->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeadChannelRequest $request)
    {
        $leadChannels = LeadChannel::find(request('ids'));

        foreach ($leadChannels as $leadChannel) {
            $leadChannel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
