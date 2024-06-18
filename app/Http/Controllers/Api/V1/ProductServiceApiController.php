<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Resources\Admin\ProductServiceResource;
use App\Models\CompanyUser;
use App\Models\ProductService;
use App\Traits\Auditable;
use App\Traits\Validation;
use Gate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductServiceApiController extends Controller
{
    use MediaUploadingTrait, Validation;

    public $successStatus = 200;

    /**
     * @api {get} /api/v1/product-services-list Product Services List
     * @apiSampleRequest off
     * @apiName Product Services List
     * @apiGroup Product/Services
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Product Services List API</span>
     *
     *   API request content-type [{"key":"Content-Type","value":"application/json"}]
     *
     *   Authorization is based on token shared while login
     *
     *   @apiHeader {String} authorization (Bearer Token) Authorization value.
     *
     *   @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer XXXXXXXXXX"
     *     }
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {Array}    data                                  Product Services List
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.torthelp.com/api/v1/product-services-list
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "status": true,
     *      "data": [{
     *          "id": 1,
     *          "name": "Logo",
     *          "description": null,
     *          "documents": null,
     *          "media": [],
     *      }]
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     */
    public function product_services_list(Request $request){
        try {
            $user = $request->user();
            $leadConversion = ProductService::join('company_users','company_users.id',"=","product_services.company_user_id")
                ->where("company_users.user_id","=",$user->id)
                ->get(['product_services.id','product_services.name','product_services.description']);

            return response()->json(['status' => true, 'data' => $leadConversion], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('CmsApiController@product_services_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {get} /api/v1/save-product-services Save Product Services List
     * @apiSampleRequest off
     * @apiName Save Product Services List
     * @apiGroup Product/Services
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Save Product Services List API</span>
     *
     *   API request content-type [{"key":"Content-Type","value":"application/json"}]
     *
     *   Authorization is based on token shared while login
     *
     *   @apiHeader {String} authorization (Bearer Token) Authorization value.
     *
     *   @apiHeaderExample {json} Header-Example:
     *     {
     *       "Authorization": "Bearer XXXXXXXXXX"
     *     }
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {Array}    data                                  Product Services List
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.torthelp.com/api/v1/product-services-list
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "status": true,
     *      "data": [{
     *          "id": 1,
     *          "name": "Logo",
     *          "description": null,
     *          "documents": null,
     *          "media": [],
     *      }]
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     */
    public function save_product_services(Request $request)
    {
        try {
            $user = $request->user();
            $userRequest = $request->all();

            $fields['name'] = [
                'required',
                'string'
            ];
            $fields['documents'] = [
                'file',
                'max:'.config('settings.file_size.general'),
                'mimes:'.config('settings.supported_file_extension.general'),
            ];

            $error = Validator::make($request->all(), $fields, [
                'name.required' => trans('label.name_required_error_msg'),
                'name.string' => trans('label.name_string_error_msg'),
                'documents.file' => trans('label.documents_file_error_msg'),
                'documents.max' => trans('label.documents_max_error_msg'),
                'documents.mimes' => trans('label.documents_mimes_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Save Product/Service');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $companyUser = CompanyUser::where('user_id',"=",$user->id)->first();
            $userRequest['company_user_id'] = $companyUser->id;
            $productService = ProductService::create($userRequest);

            if ($request->input('documents', false)) {
                $productService->addMedia(storage_path('tmp/uploads/' . basename($request->input('documents'))))->toMediaCollection('documents');
            }

            return response()->json(['status' => true, 'message' => trans('label.product_saved_success_message')], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('ProductServiceApiController@save_product_services Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    public function details_product_services(Request $request)
    {
        try {
            $user = $request->user();
            $userRequest = $request->all();

            $fields['product_service_id'] = [
                'required',
                'integer',
                'exists:product_services,id'
            ];

            $error = Validator::make($request->all(), $fields, [
                'product_service_id.required' => trans('label.product_service_id_required_error_msg'),
                'product_service_id.integer' => trans('label.product_service_id_string_error_msg'),
                'product_service_id.exists' => trans('label.product_service_id_exists_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Details Product/Service');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $getCompany = CompanyUser::where('user_id',"=",$user->id)->first();
            if(empty($getCompany)){
                Auditable::log_audit_data('ProductServiceApiController@details_product_services Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            $leadConversion = ProductService::join('company_users','company_users.id',"=","product_services.company_user_id")
                ->where("company_users.company_id","=",$getCompany->company_id)
                ->where("product_services.id","=",$userRequest['product_service_id'])
                ->first();
            return response()->json(['status' => true, 'data' => $leadConversion], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('ProductServiceApiController@details_product_services Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    public function update_product_services(UpdateProductServiceRequest $request, ProductService $productService)
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
