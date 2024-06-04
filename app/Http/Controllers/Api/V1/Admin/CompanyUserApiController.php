<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyUserRequest;
use App\Http\Requests\UpdateCompanyUserRequest;
use App\Http\Resources\Admin\CompanyUserResource;
use App\Models\CompanyUser;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyUserApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('company_user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CompanyUserResource(CompanyUser::with(['company', 'user'])->get());
    }

    public function store(StoreCompanyUserRequest $request)
    {
        $companyUser = CompanyUser::create($request->all());

        return (new CompanyUserResource($companyUser))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CompanyUser $companyUser)
    {
        abort_if(Gate::denies('company_user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CompanyUserResource($companyUser->load(['company', 'user']));
    }

    public function update(UpdateCompanyUserRequest $request, CompanyUser $companyUser)
    {
        $companyUser->update($request->all());

        return (new CompanyUserResource($companyUser))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CompanyUser $companyUser)
    {
        abort_if(Gate::denies('company_user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $companyUser->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
