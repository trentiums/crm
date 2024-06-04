<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductServiceRequest;
use App\Http\Requests\StoreProductServiceRequest;
use App\Http\Requests\UpdateProductServiceRequest;
use App\Models\CompanyUser;
use App\Models\ProductService;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductServiceController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('product_service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ProductService::query()->select(sprintf('%s.*', (new ProductService)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'product_service_show';
                $editGate      = 'product_service_edit';
                $deleteGate    = 'product_service_delete';
                $crudRoutePart = 'product-services';

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
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('documents', function ($row) {
                return $row->documents ? '<a href="' . $row->documents->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'documents']);

            return $table->make(true);
        }

        return view('admin.productServices.index');
    }

    public function create()
    {
        abort_if(Gate::denies('product_service_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productServices.create');
    }

    public function store(StoreProductServiceRequest $request)
    {
        $userRequest = $request->all();
        $user = auth()->user();
        $companyUser = CompanyUser::where('user_id',"=",$user->id)->first();
        $userRequest['company_user_id'] = $companyUser->id;
        $productService = ProductService::create($userRequest);

        if ($request->input('documents', false)) {
            $productService->addMedia(storage_path('tmp/uploads/' . basename($request->input('documents'))))->toMediaCollection('documents');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $productService->id]);
        }

        return redirect()->route('admin.product-services.index');
    }

    public function edit(ProductService $productService)
    {
        abort_if(Gate::denies('product_service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productServices.edit', compact('productService'));
    }

    public function update(UpdateProductServiceRequest $request, ProductService $productService)
    {
        $productService->update($request->all());

        if ($request->input('documents', false)) {
            if (! $productService->documents || $request->input('documents') !== $productService->documents->file_name) {
                if ($productService->documents) {
                    $productService->documents->delete();
                }
                $productService->addMedia(storage_path('tmp/uploads/' . basename($request->input('documents'))))->toMediaCollection('documents');
            }
        } elseif ($productService->documents) {
            $productService->documents->delete();
        }

        return redirect()->route('admin.product-services.index');
    }

    public function show(ProductService $productService)
    {
        abort_if(Gate::denies('product_service_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productServices.show', compact('productService'));
    }

    public function destroy(ProductService $productService)
    {
        abort_if(Gate::denies('product_service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productService->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductServiceRequest $request)
    {
        $productServices = ProductService::find(request('ids'));

        foreach ($productServices as $productService) {
            $productService->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_service_create') && Gate::denies('product_service_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ProductService();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
