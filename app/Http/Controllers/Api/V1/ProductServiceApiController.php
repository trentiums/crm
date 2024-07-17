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
     * @apiGroup Product And Services
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
     *   @apiParam {integer}     [page]    Page No
     *
     *   Validate `page` is integer
     *
     * @apiSuccess {Boolean}   status                           Response successful or not
     * @apiSuccess {Array}    data                             Product Service list
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *          "page" : 1,
     *      }
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/product-services-list
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
    public function product_services_list(Request $request)
    {
        try {
            $user = $request->user();

            $fields['page'] = [
                'nullable',
                'integer'
            ];

            $error = Validator::make($request->all(), $fields, [
                'page.integer' => trans('label.page_integer_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Product/Service List');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $leadConversion = ProductService::where("company_id", "=", $user->companyUser->company_id)
                ->select(['product_services.name', 'product_services.description', 'product_services.id'])
                ->paginate(10);

            return response()->json(['status' => true, 'data' => $leadConversion], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('CmsApiController@product_services_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {post} /api/v1/save-product-services Save Product Services
     * @apiSampleRequest off
     * @apiName Save Product Services
     * @apiGroup Product And Services
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Save Product Services API</span>
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
     * @apiParam {string}   name    Name
     *
     *    Validate `name` is required
     *
     *    Validate `name` is string
     *
     * @apiParam {string}   [description]    Description
     *
     *    Validate `description` is string
     *
     * @apiParam {file}   [document]    Document
     *
     *    Validate `document` is file
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {String}    message                              Message for error & success
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *          "name" : "demo",
     *          "description" : "demo description",
     *          "document" : "document.jpg"
     *      }
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/save-product-services
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Product/Service saved success."
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
            $fields['description'] = [
                'nullable',
                'string'
            ];
            $fields['documents'] = [
                'nullable',
                'file',
                'max:' . config('settings.file_size.general'),
                'mimes:' . config('settings.supported_file_extension.general'),
            ];

            $error = Validator::make($request->all(), $fields, [
                'name.required' => trans('label.name_required_error_msg'),
                'name.string' => trans('label.name_string_error_msg'),
                'description.string' => trans('label.description_string_error_msg'),
                'documents.file' => trans('label.documents_file_error_msg'),
                'documents.max' => trans('label.documents_max_error_msg'),
                'documents.mimes' => trans('label.documents_mimes_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Save Product/Service');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $companyUser = CompanyUser::where('user_id', "=", $user->id)->first();

            if (empty($companyUser)) {
                Auditable::log_audit_data('ProductServiceApiController@save_product_services Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            $userRequest['company_id'] = $companyUser->company_id;
            $userRequest['user_id']    = $user->id;

            $check = ProductService::where('company_id', $companyUser->company_id)->where('name', $userRequest['name'])->first();

            if (!empty($check)) {
                Auditable::log_audit_data('ProductServiceApiController@save_product_services already exists', null, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.product_service_already_exist_error_message')], $this->successStatus);
            }

            $productService = ProductService::create($userRequest);
            if ($request->file('documents')) {
                $productService->addMediaFromRequest('documents')->toMediaCollection('documents');
            }
            return response()->json(['status' => true, 'message' => trans('label.product_saved_success_message')], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('ProductServiceApiController@save_product_services Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {get} /api/v1/details-product-services Product Services Details
     * @apiSampleRequest off
     * @apiName Product Services Details
     * @apiGroup Product And Services
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Product Services Details API</span>
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
     * @apiParam {integer}   product_service_id    Product Service Id
     *
     *    Validate `product_service_id` is required
     *
     *    Validate `product_service_id` is integer
     *
     *    Validate `product_service_id` is exists or not
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {Object}    data                                 Product Services Details
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *          "product_service_id" : 1,
     *      }
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/details-product-services
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "status": true,
     *      "data": {
     *          "id": 1,
     *          "name": "Logo",
     *          "description": null,
     *          "documents": null,
     *          "media": [],
     *      }
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     */
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

            $validationResponse = $this->check_validation($fields, $error, 'Product/Service Details');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $getCompany = CompanyUser::where('user_id', "=", $user->id)->first();
            if (empty($getCompany)) {
                Auditable::log_audit_data('ProductServiceApiController@details_product_services Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            $productService = ProductService::where('company_id', $getCompany->company_id)->where('id', $userRequest['product_service_id'])->first();

            return response()->json(['status' => true, 'data' => $productService], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('ProductServiceApiController@details_product_services Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {post} /api/v1/update-product-services Update Product Services
     * @apiSampleRequest off
     * @apiName Update Product Services
     * @apiGroup Product And Services
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Update Product Services API</span>
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
     * @apiParam {integer}   product_service_id    Product Service Id
     *
     *    Validate `product_service_id` is required
     *
     *    Validate `product_service_id` is integer
     *
     *    Validate `product_service_id` is exists or not
     *
     * @apiParam {string}   name    Name
     *
     *    Validate `name` is required
     *
     *    Validate `name` is string
     *
     * @apiParam {string}   [description]    Description
     *
     *    Validate `description` is string
     *
     * @apiParam {file}   [documents]    Documents
     *
     *    Validate `documents` is file
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {String}    message                              Message for error & success
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *          "product_service_id" : 1,
     *          "name" : "demo",
     *          "description" : "demo description",
     *          "documents" : "document.jpg"
     *      }
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/update-product-services
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Product/Service updated success."
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     */
    public function update_product_services(Request $request)
    {
        try {
            $user = $request->user();
            $userRequest = $request->all();

            $fields['product_service_id'] = [
                'required',
                'integer',
                'exists:product_services,id,deleted_at,NULL'
            ];
            $fields['name'] = [
                'required',
                'string'
            ];
            $fields['description'] = [
                'nullable',
                'string'
            ];
            $fields['documents'] = [
                'nullable',
                'file',
                'max:' . config('settings.file_size.general'),
                'mimes:' . config('settings.supported_file_extension.general'),
            ];

            $error = Validator::make($request->all(), $fields, [
                'product_service_id.required' => trans('label.product_service_id_required_error_msg'),
                'product_service_id.integer' => trans('label.product_service_id_integer_error_msg'),
                'product_service_id.exists' => trans('label.product_service_id_exists_error_msg'),
                'name.required' => trans('label.name_required_error_msg'),
                'name.string' => trans('label.name_string_error_msg'),
                'description.string' => trans('label.description_string_error_msg'),
                'documents.file' => trans('label.documents_file_error_msg'),
                'documents.max' => trans('label.documents_max_error_msg'),
                'documents.mimes' => trans('label.documents_mimes_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Update Product/Service');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $getCompany = CompanyUser::where('user_id', "=", $user->id)->first();
            if (empty($getCompany)) {
                Auditable::log_audit_data('ProductServiceApiController@details_product_services Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            $check = ProductService::where('company_id', $getCompany->company_id)->where('name', $userRequest['name'])->whereNot('id', $userRequest['product_service_id'])->first();

            if (!empty($check)) {
                Auditable::log_audit_data('ProductServiceApiController@save_product_services already exists', null, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.product_service_already_exist_error_message')], $this->successStatus);
            }

            $productService = ProductService::find($userRequest['product_service_id']);
            $productService->update($request->all());

            if ($request->file('documents')) {
                if ($productService->documents) {
                    $productService->documents->delete();
                }
                $productService->addMediaFromRequest('documents')->toMediaCollection('documents');
            } elseif ($productService->documents) {
                $productService->documents->delete();
            }

            return response()->json(['status' => true, 'message' => trans('label.product_update_success_message')], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('ProductServiceApiController@save_product_services Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {post} /api/v1/delete-product-services Delete Product Services
     * @apiSampleRequest off
     * @apiName Delete Product Services
     * @apiGroup Product And Services
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Delete Product Services API</span>
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
     * @apiParam {integer}   product_service_id    Product Service Id
     *
     *    Validate `product_service_id` is required
     *
     *    Validate `product_service_id` is integer
     *
     *    Validate `product_service_id` is exists or not
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {String}    message                              Message for error & success
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *          "product_service_id" : 1,
     *      }
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/delete-product-services
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Product/Service deleted success."
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     */
    public function delete_product_services(Request $request)
    {
        try {
            $user = $request->user();
            $userRequest = $request->all();

            $fields['product_service_id'] = [
                'required',
                'integer',
                'exists:product_services,id,deleted_at,NULL'
            ];

            $error = Validator::make($request->all(), $fields, [
                'product_service_id.required' => trans('label.product_service_id_required_error_msg'),
                'product_service_id.integer' => trans('label.product_service_id_integer_error_msg'),
                'product_service_id.exists' => trans('label.product_service_id_exists_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Delete Product/Service');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $getCompany = CompanyUser::where('user_id', "=", $user->id)->first();
            if (empty($getCompany)) {
                Auditable::log_audit_data('ProductServiceApiController@delete_product_services Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            $productService = ProductService::where('company_id', $getCompany->company_id)->where('id', $userRequest['product_service_id'])->first();

            if ($productService) {
                if(count($productService->leads)>0){
                    Auditable::log_audit_data('ProductServiceApiController@delete_product_services Exception', $user, config('settings.log_type')[1], $userRequest);
                    return response()->json(['status' => false, 'message' => trans("label.product_service_can't_delete")], $this->successStatus);
                }

                if ($productService->documents) {
                    $productService->documents->delete();
                }
                $productService->delete();
                return response()->json(['status' => true, 'message' => trans('label.product_delete_success_message')], $this->successStatus);
            } else {
                Auditable::log_audit_data('ProductServiceApiController@details_product_services Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('ProductServiceApiController@save_product_services Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
}
