<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadConversionRequest;
use App\Http\Requests\UpdateLeadConversionRequest;
use App\Http\Resources\Admin\LeadConversionResource;
use App\Models\LeadConversion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadConversionApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lead_conversion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeadConversionResource(LeadConversion::all());
    }

    public function store(StoreLeadConversionRequest $request)
    {
        $leadConversion = LeadConversion::create($request->all());

        return (new LeadConversionResource($leadConversion))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LeadConversion $leadConversion)
    {
        abort_if(Gate::denies('lead_conversion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeadConversionResource($leadConversion);
    }

    public function update(UpdateLeadConversionRequest $request, LeadConversion $leadConversion)
    {
        $leadConversion->update($request->all());

        return (new LeadConversionResource($leadConversion))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LeadConversion $leadConversion)
    {
        abort_if(Gate::denies('lead_conversion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leadConversion->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
