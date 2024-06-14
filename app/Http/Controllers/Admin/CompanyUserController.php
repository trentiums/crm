<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCompanyUserRequest;
use App\Http\Requests\StoreCompanyUserRequest;
use App\Http\Requests\UpdateCompanyUserRequest;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Role;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CompanyUserController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('company_user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CompanyUser::with(['company', 'user'])->select(sprintf('%s.*', (new CompanyUser)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'company_user_show';
                $editGate      = 'company_user_edit';
                $deleteGate    = 'company_user_delete';
                $crudRoutePart = 'company-users';

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
            $table->addColumn('company_name', function ($row) {
                return $row->company ? $row->company->name : '';
            });

            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'company', 'user']);

            return $table->make(true);
        }

        return view('admin.companyUsers.index');
    }

    public function create()
    {
        $user = Auth::user();
        $companies = NULL;
        if($user->user_role == array_flip(Role::ROLES)['Admin']){
            $companies = Company::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        }
        abort_if(Gate::denies('company_user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.companyUsers.create', compact('companies', 'users'));
    }

    public function store(StoreCompanyUserRequest $request)
    {
        $userRequest = $request->all();
        $user = new User();
        $user->name = $userRequest['name'];
        $user->email = $userRequest['email'];
        $user->password = $userRequest['password'];
        $user->email_verified_at = date("Y-m-d H:i:s");
        $user->user_role = array_flip(Role::ROLES)['Company Staff'];
        $userDetails = $user->save();
        $user->roles()->sync(array_flip(Role::ROLES)['Company Staff']);

        $userMain = Auth::user();
        if($userMain->user_role == array_flip(Role::ROLES)['Admin']){
            $company_id = $userRequest['company_id'];
        }
        else{
            $company = Company::where("user_id","=",$userMain->id)->first();
            $company_id = $company->id;
        }

        $companyUser = new CompanyUser();
        $companyUser->user_id = $user->id;
        $companyUser->company_id = $company_id;
        $companyUser->save();

        return redirect()->route('admin.company-users.index');
    }

    public function edit(CompanyUser $companyUser)
    {
        abort_if(Gate::denies('company_user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $companies = Company::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $companyUser->load('company', 'user');

        return view('admin.companyUsers.edit', compact('companies', 'companyUser', 'users'));
    }

    public function update(UpdateCompanyUserRequest $request, CompanyUser $companyUser)
    {
        $companyUser->update($request->all());

        return redirect()->route('admin.company-users.index');
    }

    public function show(CompanyUser $companyUser)
    {
        abort_if(Gate::denies('company_user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $companyUser->load('company', 'user');

        return view('admin.companyUsers.show', compact('companyUser'));
    }

    public function destroy(CompanyUser $companyUser)
    {
        abort_if(Gate::denies('company_user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $companyUser->delete();

        return back();
    }

    public function massDestroy(MassDestroyCompanyUserRequest $request)
    {
        $companyUsers = CompanyUser::find(request('ids'));

        foreach ($companyUsers as $companyUser) {
            $companyUser->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
