<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Http\Resources\Admin\LeadResource;
use App\Models\Lead;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lead_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeadResource(Lead::with(['lead_status', 'lead_channel', 'product_services', 'lead_conversion'])->get());
    }

    public function store(StoreLeadRequest $request)
    {
        $lead = Lead::create($request->all());
        $lead->product_services()->sync($request->input('product_services', []));

        return (new LeadResource($lead))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Lead $lead)
    {
        abort_if(Gate::denies('lead_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeadResource($lead->load(['lead_status', 'lead_channel', 'product_services', 'lead_conversion']));
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $lead->update($request->all());
        $lead->product_services()->sync($request->input('product_services', []));

        return (new LeadResource($lead))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Lead $lead)
    {
        abort_if(Gate::denies('lead_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lead->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
