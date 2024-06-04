<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProductServiceRequest;
use App\Http\Requests\UpdateProductServiceRequest;
use App\Http\Resources\Admin\ProductServiceResource;
use App\Models\ProductService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductServiceApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('product_service_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductServiceResource(ProductService::all());
    }

    public function store(StoreProductServiceRequest $request)
    {
        $productService = ProductService::create($request->all());

        if ($request->input('documents', false)) {
            $productService->addMedia(storage_path('tmp/uploads/' . basename($request->input('documents'))))->toMediaCollection('documents');
        }

        return (new ProductServiceResource($productService))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ProductService $productService)
    {
        abort_if(Gate::denies('product_service_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductServiceResource($productService);
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

        return (new ProductServiceResource($productService))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ProductService $productService)
    {
        abort_if(Gate::denies('product_service_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productService->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
