<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadChannelRequest;
use App\Http\Requests\UpdateLeadChannelRequest;
use App\Http\Resources\Admin\LeadChannelResource;
use App\Models\LeadChannel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadChannelsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lead_channel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeadChannelResource(LeadChannel::all());
    }

    public function store(StoreLeadChannelRequest $request)
    {
        $leadChannel = LeadChannel::create($request->all());

        return (new LeadChannelResource($leadChannel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LeadChannel $leadChannel)
    {
        abort_if(Gate::denies('lead_channel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LeadChannelResource($leadChannel);
    }

    public function update(UpdateLeadChannelRequest $request, LeadChannel $leadChannel)
    {
        $leadChannel->update($request->all());

        return (new LeadChannelResource($leadChannel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LeadChannel $leadChannel)
    {
        abort_if(Gate::denies('lead_channel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leadChannel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
