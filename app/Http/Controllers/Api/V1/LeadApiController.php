<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CompanyUser;
use App\Models\Country;
use App\Models\Lead;
use App\Models\LeadHistory;
use App\Models\LeadProductService;
use App\Models\Role;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use Exception;
use DB;
use Hash;
use App\Traits\Validation;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LeadApiController extends Controller
{
    use Validation;

    public $successStatus = 200;
    /**
     * @api {get} /api/v1/lead-list Lead List
     * @apiSampleRequest off
     * @apiName Lead List
     * @apiGroup Lead
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Lead List API</span>
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
     * @apiParam {string}   [start_date]    Start Date
     *
     *    Validate `start_date` is string
     *
     *    Validate `start_date` is valid date (YYY-MM-DD)
     *
     *    Validate `start_date` is not grater than `end_date`
     *
     * @apiParam {string}   [end_date]    Start Date
     *
     *    Validate `end_date` is string
     *
     *    Validate `end_date` is valid date (YYY-MM-DD)
     *
     *    Validate `end_date` is not less than `start_date`
     *
     * @apiParam {string}   [order_by]    Order By
     *
     *    Validate `order_by` is integer
     *
     *    Validate `order_by` must be from [1 = created_at,2 = name, 3 = email, 4 = company_user_id]
     *
     * @apiParam {string}   [sort_order]    Order By
     *
     *    Validate `sort_order` is integer
     *
     *    Validate `sort_order` must be from [1 = ASC,2 = DESC]
     *
     *   @apiParam {integer}     [page]    Page No
     *
     *   Validate `page` is integer
     *
     * @apiParam {string}   [search]    Search
     *
     *    Validate `search` is string
     *
     *    You can search (email, name, phone number)
     *
     *   @apiParam {integer}     [lead_status_id]    Lead Status ID
     *
     *   Validate `lead_status_id` is integer
     *
     *   Validate `lead_status_id` is exist or not
     *
     *   @apiParam {integer}     [lead_channel_id]    Lead Channel ID
     *
     *   Validate `lead_channel_id` is integer
     *
     *   Validate `lead_channel_id` is exist or not
     *
     *   @apiParam {integer}     [lead_conversion_id]    Lead Conversion ID
     *
     *   Validate `lead_conversion_id` is integer
     *
     *   Validate `lead_conversion_id` is exist or not
     *
     * @apiParamExample {queryParam} Request-Example:
     *    {
     *           "start_date" : "2024-01-01",
     *           "end_date" : "2024-08-01",
     *           "order_by": 1,
     *           "sort_order": 2,
     *           "search": "bhargav",
     *    }
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {Array}    data                                  Lead List
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/lead-list
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": true,
     *          "data": {
     *              "current_page": 1,
     *              "data": [
     *               {
     *                  "id": 1,
     *                  "company_user_id": 1,
     *                  "name": "Bhargav123",
     *                  "email": "bhargav960143@gmail.com",
     *                  "phone": null,
     *                  "company_name": "Trentium",
     *                  "company_size": "30",
     *                  "company_website": "https://www.trentiums.com",
     *                  "budget": "1500 INR",
     *                  "time_line": "2 Hours",
     *                  "description": "Banner development",
     *                  "deal_amount": "1200.00",
     *                  "win_close_reason": null,
     *                  "deal_close_date": "2024-06-18",
     *                  "created_at": "2024-06-19 07:18:14",
     *                  "updated_at": "2024-06-19 13:38:47",
     *                  "deleted_at": null,
     *                  "lead_status_id": 1,
     *                  "lead_channel_id": 1,
     *                  "lead_conversion_id": 1,
     *                  "country_id": null,
     *                  "documents": {
     *                      "id": 20,
     *                      "model_type": "App\\Models\\Lead",
     *                      "model_id": 1,
     *                      "uuid": "8f17bd69-e87a-4da7-9b38-e3e60ad78ae6",
     *                      "collection_name": "documents",
     *                      "name": "user-form",
     *                      "file_name": "user-form.png",
     *                      "mime_type": "image/png",
     *                      "disk": "public",
     *                      "conversions_disk": "public",
     *                      "size": 9013,
     *                      "manipulations": [],
     *                      "custom_properties": [],
     *                      "generated_conversions": {
     *                          "thumb": true,
     *                          "preview": true
     *                      },
     *                      "responsive_images": [],
     *                      "order_column": 1,
     *                      "created_at": "2024-06-19T13:38:47.000000Z",
     *                      "updated_at": "2024-06-19T13:38:47.000000Z",
     *                      "original_url": "http://127.0.0.1:8000/storage/20/user-form.png",
     *                      "preview_url": "http://127.0.0.1:8000/storage/20/conversions/user-form-preview.jpg"
     *                  },
     *                  "lead_status": {
     *                      "id": 1,
     *                      "name": "New",
     *                      "created_at": "2024-06-04 10:04:37",
     *                      "updated_at": "2024-06-04 10:04:37",
     *                      "deleted_at": null
     *                  },
     *                  "lead_channel": {
     *                      "id": 1,
     *                      "name": "Website Forms",
     *                      "created_at": "2024-06-04 10:02:53",
     *                      "updated_at": "2024-06-04 10:02:53",
     *                      "deleted_at": null
     *                  },
     *                  "product_services": [],
     *                  "lead_conversion": {
     *                      "id": 1,
     *                      "name": "Proposal Stage",
     *                      "created_at": "2024-06-04 10:05:39",
     *                      "updated_at": "2024-06-04 10:05:39",
     *                      "deleted_at": null
     *                  },
     *                  "company_user": {
     *                      "id": 1,
     *                      "created_at": "2024-06-04 15:47:00",
     *                      "updated_at": null,
     *                      "deleted_at": null,
     *                      "company_id": 1,
     *                      "user_id": 2,
     *                      "user": {
     *                          "id": 2,
     *                          "name": "Trentium Solution Private Limited",
     *                          "email": "info@trentiums.com",
     *                          "email_verified_at": null,
     *                          "user_role": 2,
     *                          "created_at": "2024-06-04 10:07:02",
     *                          "updated_at": "2024-06-04 10:07:02",
     *                          "deleted_at": null
     *                      }
     *                  },
     *                  "media": [
     *                      {
     *                          "id": 20,
     *                          "model_type": "App\\Models\\Lead",
     *                          "model_id": 1,
     *                          "uuid": "8f17bd69-e87a-4da7-9b38-e3e60ad78ae6",
     *                          "collection_name": "documents",
     *                          "name": "user-form",
     *                          "file_name": "user-form.png",
     *                          "mime_type": "image/png",
     *                          "disk": "public",
     *                          "conversions_disk": "public",
     *                          "size": 9013,
     *                          "manipulations": [],
     *                          "custom_properties": [],
     *                          "generated_conversions": {
     *                              "thumb": true,
     *                              "preview": true
     *                          },
     *                          "responsive_images": [],
     *                          "order_column": 1,
     *                          "created_at": "2024-06-19T13:38:47.000000Z",
     *                          "updated_at": "2024-06-19T13:38:47.000000Z",
     *                          "original_url": "http://127.0.0.1:8000/storage/20/user-form.png",
     *                          "preview_url": "http://127.0.0.1:8000/storage/20/conversions/user-form-preview.jpg"
     *                      }
     *                  ]
     *                 }
     *               ],
     *              "first_page_url": "https://crm.trentiums.com/api/v1/lead-list?page=1",
     *              "from": null,
     *              "last_page": 1,
     *              "last_page_url": "https://crm.trentiums.com/api/v1/lead-list?page=1",
     *              "links": [
     *                  {
     *                      "url": null,
     *                      "label": "&laquo; Previous",
     *                      "active": false
     *                  },
     *                  {
     *                      "url": "https://crm.trentiums.com/api/v1/lead-list?page=1",
     *                      "label": "1",
     *                      "active": true
     *                  },
     *                  {
     *                      "url": null,
     *                      "label": "Next &raquo;",
     *                      "active": false
     *                  }
     *              ],
     *              "next_page_url": null,
     *              "path": "https://crm.trentiums.com/api/v1/lead-list",
     *              "per_page": 10,
     *              "prev_page_url": null,
     *              "to": null,
     *              "total": 0
     *          }
     *      }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     */
    public function lead_list(Request $request)
    {
        try {
            $userRequest = $request->all();
            $user = $request->user();
            $fields = [
                'start_date' => [
                    'string',
                    'date',
                    'date_format:' . config('settings.date_format')
                ],
                'end_date' => [
                    'string',
                    'date',
                    'date_format:' . config('settings.date_format'),
                    'after:start_date'
                ],
                'order_by' => [
                    'integer',
                    'in:' . implode(',', array_keys(Lead::ORDER_BY)),
                ],
                'sort_order' => [
                    'integer',
                    'in:' . implode(',', array_keys(Lead::ORDER)),
                ],
                'search' => [
                    'string',
                ],
                'lead_status_id' => [
                    'integer',
                    'exists:lead_statuses,id',
                ],
                'lead_channel_id' => [
                    'integer',
                    'exists:lead_channels,id',
                ],
                'lead_conversion_id' => [
                    'integer',
                    'exists:lead_conversions,id',
                ]
            ];

            $error = Validator::make($userRequest, $fields, [
                'search.string' => trans('label.search_string_error_msg'),
                'start_date.string' => trans('label.start_date_string_error_msg'),
                'start_date.date' => trans('label.start_date_date_error_msg'),
                'start_date.date_format' => trans('label.start_date_format_string_error_msg'),
                'end_date.string' => trans('label.end_date_string_error_msg'),
                'end_date.date' => trans('label.end_date_date_error_msg'),
                'end_date.date_format' => trans('label.end_date_format_string_error_msg'),
                'end_date.after' => trans('label.end_date_after_string_error_msg'),
                'order_by.integer' => trans('label.order_by_integer_error_msg'),
                'order_by.in' => trans('label.order_by_in_error_msg'),
                'sort_order.integer' => trans('label.sort_order_integer_error_msg'),
                'sort_order.in' => trans('label.sort_order_in_error_msg'),
                'lead_status_id.integer' => trans('label.lead_status_id_integer_error_msg'),
                'lead_status_id.exists' => trans('label.lead_status_id_exists_error_msg'),
                'lead_channel_id.integer' => trans('label.lead_channel_id_integer_error_msg'),
                'lead_channel_id.exists' => trans('label.lead_channel_id_exists_error_msg'),
                'lead_conversion_id.integer' => trans('label.lead_conversion_id_integer_error_msg'),
                'lead_conversion_id.exists' => trans('label.lead_conversion_id_exists_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Lead List');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            if(empty($user->companyUser)){
                Auditable::log_audit_data('ProductServiceApiController@lead_list Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            $leadConversion = Lead::with(['lead_status', 'lead_channel', 'product_services', 'lead_conversion', 'company_user.user'])
                ->whereHas('company_user', function ($query) use ($user) {
                    $query->where('company_id', $user->companyUser->company_id);
            });

            if (isset($userRequest['start_date']) && !empty($userRequest['start_date']) && isset($userRequest['end_date']) && !empty($userRequest['end_date'])) {
                $leadConversion->whereDate('leads.created_at', ">=", $userRequest['start_date']);
                $leadConversion->whereDate('leads.created_at', "<=", $userRequest['end_date']);
            }

            if (isset($userRequest['lead_status_id']) && !empty($userRequest['lead_status_id'])) {
                $leadConversion->where('leads.lead_status_id', $userRequest['lead_status_id']);
            }

            if (isset($userRequest['lead_channel_id']) && !empty($userRequest['lead_channel_id'])) {
                $leadConversion->where('leads.lead_channel_id', $userRequest['lead_channel_id']);
            }

            if (isset($userRequest['lead_conversion_id']) && !empty($userRequest['lead_conversion_id'])) {
                $leadConversion->where('leads.lead_conversion_id', $userRequest['lead_conversion_id']);
            }

            if (isset($userRequest['search']) && !empty($userRequest['search'])) {
                $leadConversion->where('leads.name', 'LIKE', '%' . $userRequest['search'] . '%')
                    ->orWhere('leads.email', 'LIKE', '%' . $userRequest['search'] . '%')
                    ->orWhere('leads.phone', 'LIKE', '%' . $userRequest['search'] . '%');
            }

            if (isset($userRequest['order_by']) && !empty($userRequest['order_by']) && isset($userRequest['sort_order']) && !empty($userRequest['sort_order'])) {
                if ($userRequest['order_by'] == array_flip(Lead::ORDER_BY)['created_at']) {
                    $order_by = "leads.created_at";
                } else if ($userRequest['order_by'] == array_flip(Lead::ORDER_BY)['name']) {
                    $order_by = "leads.name";
                } else if ($userRequest['order_by'] == array_flip(Lead::ORDER_BY)['email']) {
                    $order_by = "leads.email";
                } else if ($userRequest['order_by'] == array_flip(Lead::ORDER_BY)['company_user_id']) {
                    $order_by = "users.name";
                }
                $leadConversion->orderBy($order_by, Lead::ORDER[$userRequest['sort_order']]);
            }

            $leadConversion = $leadConversion->paginate(10);

            return response()->json(['status' => true, 'data' => $leadConversion], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('LeadApiController@lead_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {post} /api/v1/save-lead Save Lead
     * @apiSampleRequest off
     * @apiName Save Lead
     * @apiGroup Lead
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Save Lead API</span>
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
     * @apiParam {string}   [email]    Email
     *
     *    Validate `email` is required if phone is not entered
     *
     *    Validate `email` is string
     *
     *    Validate `email` is valid email
     *
     *  @apiParam {String}     [country_code_alpha]     Country Code Alpha
     *
     *    Validate `country_code_alpha` is required with phone number
     *
     *    Validate `country_code_alpha` is string
     *
     *    Validate `country_code_alpha` is exists or not
     *
     * @apiParam {Integer}   [phone]    Phone
     *
     *    Validate `phone` is required if email is not entered
     *
     *    Validate `phone` is numeric
     *
     *    Validate `phone` is validate by country code alpha
     *
     * @apiParam {string}   [company_name]    Company Name
     *
     *    Validate `company_name` is string
     *
     * @apiParam {Integer}   [company_size]    Company Size
     *
     *    Validate `company_size` is integer
     *
     * @apiParam {string}   [company_website]    Company Website
     *
     *    Validate `company_website` is string
     *
     *  @apiParam {Integer}     lead_status_id     Lead Status Id
     *
     *    Validate `lead_status_id` is required
     *
     *    Validate `lead_status_id` is integer
     *
     *    Validate `lead_status_id` is exists or not
     *
     *  @apiParam {Integer}     lead_channel_id     Lead Channel Id
     *
     *    Validate `lead_channel_id` is required
     *
     *    Validate `lead_channel_id` is integer
     *
     *    Validate `lead_channel_id` is exists or not
     *
     *   @apiParam {Array}     product_services      Product Services
     *
     *    Validate `product_services` is required
     *
     *    Validate `product_services` is array
     *
     *    Validate `product_services` contains only integer
     *
     *    Validate `product_services` value exist or not
     *
     *  @apiParam {Integer}     lead_conversion_id     Lead Conversion Id
     *
     *    Validate `lead_conversion_id` is required
     *
     *    Validate `lead_conversion_id` is integer
     *
     *    Validate `lead_conversion_id` is exists or not
     *
     * @apiParam {string}   [budget]    Budget
     *
     *    Validate `budget` is string
     *
     * @apiParam {string}   [time_line]    Timeline
     *
     *    Validate `time_line` is string
     *
     * @apiParam {string}   [description]    description
     *
     *    Validate `description` is string
     *
     * @apiParam {numeric}   [deal_amount]    Deal Amount
     *
     *    Validate `deal_amount` is numeric
     *
     *
     * @apiParam {string}   [win_close_reason]    Win Close Reason
     *
     *    Validate `win_close_reason` is string
     *
     * @apiParam {date}   [deal_close_date]    Deal Close Date
     *
     *    Validate `deal_close_date` is date
     *
     * @apiParam {array}   [documents]    Document
     *
     *    Validate `documents` is array
     *
     *    Validate `documents` are file
     *
     *    Validate `documents` are less than equal to 5 MB
     *
     *    Validate `documents` file support jpg,jpeg,png,mp4,avi,mov,pdf,doc mimetype
     *
     * @apiParamExample {bodyJson} Request-Example:
     *    {
     *    "name": "Bhargav",
     *    "email": "bhargav960143@gmail.com",
     *    "country_code_alpha": "IN"
     *    "phone": "9662062016",
     *    "company_name": "Trentium",
     *    "company_size": "30",
     *    "company_website": "https://www.trentiums.com",
     *    "lead_status_id": 1,
     *    "lead_channel_id": 1,
     *    "product_services":[1,2],
     *    "lead_conversion_id": 1,
     *    "budget": "1500 INR",
     *    "time_line": "2 Hours",
     *    "description": "Banner development",
     *    "deal_amount": "1200",
     *    "win_close_reason": "",
     *    "deal_close_date": "2024-06-18"
     *    "documents" : "Demo.jpg"
     *    }
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {String}    message                              Message for error & success
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/save-lead
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": true,
     *          "message": "Lead saved successfully."
     *      }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *          "status": false,
     *          "message": "Something wen't wrong please try again"
     *     }
     */
    public function save_lead(Request $request)
    {
        try {
            $userRequest = $request->all();
            $user = $request->user();
            $fields = [
                'name' => [
                    'string',
                    'required',
                ],
                'email' => [
                    'required_without:phone',
                    'string',
                    'min:2',
                    'email:rfc,dns',
                ],
                'country_code_alpha' => [
                    'required_with:phone',
                    'string',
                    'exists:countries,country_code_alpha,deleted_at,NULL',
                ],
                'phone' => [
                    'required_without:email',
                    'required_with:country_code_alpha',
                    'numeric',
                    'phone:country_code_alpha',
                ],
                'company_name' => [
                    'string',
                    'nullable',
                ],
                'company_size' => [
                    'integer',
                    'nullable',
                ],
                'company_website' => [
                    'string',
                    'nullable',
                ],
                'lead_status_id' => [
                    'required',
                    'exists:lead_statuses,id',
                    'integer',
                ],
                'lead_channel_id' => [
                    'required',
                    'exists:lead_channels,id',
                    'integer',
                ],
                'product_services.*' => [
                    'integer',
                    'exists:product_services,id,deleted_at,NULL'
                ],
                'product_services' => [
                    'required',
                    'array',
                ],
                'lead_conversion_id' => [
                    'required',
                    'exists:lead_conversions,id',
                    'integer',
                ],
                'budget' => [
                    'nullable',
                    'string'
                ],
                'description' => [
                    'nullable',
                    'string'
                ],
                'deal_amount' => [
                    'nullable',
                    'numeric'
                ],
                'time_line' => [
                    'string',
                    'nullable',
                ],
                'win_close_reason' => [
                    'string',
                    'nullable',
                ],
                'deal_close_date' => [
                    'date_format:' . config('panel.date_format'),
                    'nullable',
                ],
                'documents' => [
                    'nullable',
                    'array'
                ],
                'documents.*' => [
                    'required',
                    'file',
                    'max:' . config('settings.file_size.general'),
                    'mimes:' . config('settings.supported_file_extension.general'),
                ]
            ];

            $error = Validator::make($userRequest, $fields, [
                'name.required' => trans('label.lead_name_required_error_msg'),
                'name.string' => trans('label.lead_name_string_error_msg'),
                'email.required_without' => trans('label.email_required_error_msg'),
                'email.string' => trans('label.email_string_error_msg'),
                'email.email' => trans('label.email_format_error_msg'),
                'email.exists' => trans('label.email_exists_error_msg'),
                'country_code_alpha.required_with' => trans('label.lead_country_code_alpha_required_with_error_msg'),
                'country_code_alpha.string' => trans('label.lead_country_code_alpha_string_error_msg'),
                'country_code_alpha.exists' => trans('label.lead_country_code_alpha_exists_error_msg'),
                'phone.required_without' => trans('label.lead_phone_required_without_error_msg'),
                'phone.required_with' => trans('label.lead_phone_required_without_error_msg'),
                'phone.numeric' => trans('label.lead_phone_numeric_error_msg'),
                'phone.phone' => trans('label.lead_phone_phone_error_msg'),
                'company_name.string' => trans('label.company_name_string_error_msg'),
                'company_size.string' => trans('label.company_size_string_error_msg'),
                'company_website.string' => trans('label.company_website_string_error_msg'),
                'lead_status_id.required' => trans('label.lead_status_id_required_error_msg'),
                'lead_status_id.exists' => trans('label.lead_status_id_exists_error_msg'),
                'lead_status_id.integer' => trans('label.lead_status_id_integer_error_msg'),
                'lead_channel_id.required' => trans('label.lead_channel_id_required_error_msg'),
                'lead_channel_id.exists' => trans('label.lead_channel_id_exists_error_msg'),
                'lead_channel_id.integer' => trans('label.lead_channel_id_integer_error_msg'),
                'product_services.*.required' => trans('label.product_services_required_error_msg'),
                'product_services.*.exists' => trans('label.product_services_exists_error_msg'),
                'product_services.required' => trans('label.product_services_required_error_msg'),
                'product_services.array' => trans('label.product_services_array_error_msg'),
                'lead_conversion_id.required' => trans('label.lead_conversion_id_required_error_msg'),
                'lead_conversion_id.exists' => trans('label.lead_conversion_id_exists_error_msg'),
                'lead_conversion_id.integer' => trans('label.lead_conversion_id_integer_error_msg'),
                'budget.string' => trans('label.budget_string_error_msg'),
                'description.string' => trans('label.lead_description_string_error_msg'),
                'deal_amount.integer' => trans('label.lead_deal_amount_integer_error_msg'),
                'win_close_reason.string' => trans('label.win_close_reason_string_error_msg'),
                'time_line.string' => trans('label.time_line_string_error_msg'),
                'win_close_reason.string' => trans('label.win_close_reason_string_error_msg'),
                'deal_close_date.string' => trans('label.deal_close_date_string_error_msg'),
                'documents.array' => trans('label.documents_array_error_msg')
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Save Lead');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            if ($request->country_code_alpha) {
                $country = Country::where('country_code_alpha', $request->country_code_alpha)->first();
            }

            $companyUser = CompanyUser::where("user_id", "=", $user->id)->first();

            if ($companyUser) {
                $lead = new Lead();
                $lead->company_user_id = $companyUser->id;
                $lead->name = $userRequest['name'];
                $lead->phone = $userRequest['phone'] ?? null;
                $lead->email = $userRequest['email'] ?? null;
                $lead->company_name = $userRequest['company_name'] ?? null;
                $lead->company_size = $userRequest['company_size'] ?? null;
                $lead->company_website = $userRequest['company_website'] ?? null;
                $lead->lead_status_id = $userRequest['lead_status_id'];
                $lead->lead_channel_id = $userRequest['lead_channel_id'];
                $lead->lead_conversion_id = $userRequest['lead_conversion_id'];
                $lead->budget = $userRequest['budget'] ?? null;
                $lead->time_line = $userRequest['time_line'] ?? null;
                $lead->description = $userRequest['description'] ?? null;
                $lead->deal_amount = $userRequest['deal_amount'] ?? null;
                $lead->win_close_reason = $userRequest['win_close_reason'] ?? null;
                $lead->deal_close_date = $userRequest['deal_close_date'] ?? null;
                $lead->country_id = isset($country) ? $country->id : null;
                $lead->save();

                if (isset($userRequest['product_services']) && !empty($userRequest['product_services'])) {
                    $arrData = [];
                    foreach ($userRequest['product_services'] as $keyProduct => $valueProduct) {
                        $arrData[$keyProduct]['lead_id'] = $lead->id;
                        $arrData[$keyProduct]['product_service_id'] = $valueProduct;
                    }

                    LeadProductService::insert($arrData);
                }

                if ($request->file('documents')) {
                    foreach ($request->file('documents') as $file) {
                        $lead->addMedia($file)->toMediaCollection('documents');
                    }
                }

                $leadHistory = new LeadHistory();
                $leadHistory->lead_id = $lead->id;
                $leadHistory->company_user_id = $companyUser->id;
                $leadHistory->description = "Lead Created";
                $leadHistory->created_at = date("Y-m-d H:i:s");
                $leadHistory->save();

                return response()->json(['status' => true, 'message' => trans('label.lead_insert_success_msg')], $this->successStatus);
            } else {
                Auditable::log_audit_data('ProductServiceApiController@save_lead Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('LeadApiController@save_lead Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {post} /api/v1/update-lead Update Lead
     * @apiSampleRequest off
     * @apiName Update Lead
     * @apiGroup Lead
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Update Lead API</span>
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
     *  @apiParam {Integer}     lead_id     Lead Id
     *
     *    Validate `lead_id` is required
     *
     *    Validate `lead_id` is integer
     *
     *    Validate `lead_id` is exists or not
     *
     * @apiParam {string}   name    Name
     *
     *    Validate `name` is required
     *
     *    Validate `name` is string
     *
     * @apiParam {string}   [email]    Email
     *
     *    Validate `email` is required if phone is not entered
     *
     *    Validate `email` is string
     *
     *    Validate `email` is valid email
     *
     *  @apiParam {String}     [country_code_alpha]     Country Code Alpha
     *
     *    Validate `country_code_alpha` is required with phone number
     *
     *    Validate `country_code_alpha` is string
     *
     *    Validate `country_code_alpha` is exists or not
     *
     * @apiParam {Integer}   [phone]    Phone
     *
     *    Validate `phone` is required if email is not entered
     *
     *    Validate `phone` is numeric
     *
     *    Validate `phone` is validate by country code alpha
     *
     * @apiParam {string}   [company_name]    Company Name
     *
     *    Validate `company_name` is string
     *
     * @apiParam {Integer}   [company_size]    Company Size
     *
     *    Validate `company_size` is integer
     *
     * @apiParam {string}   [company_website]    Company Website
     *
     *    Validate `company_website` is string
     *
     *  @apiParam {Integer}     lead_status_id     Lead Status Id
     *
     *    Validate `lead_status_id` is required
     *
     *    Validate `lead_status_id` is integer
     *
     *    Validate `lead_status_id` is exists or not
     *
     *  @apiParam {Integer}     lead_channel_id     Lead Channel Id
     *
     *    Validate `lead_channel_id` is required
     *
     *    Validate `lead_channel_id` is integer
     *
     *    Validate `lead_channel_id` is exists or not
     *
     *   @apiParam {Array}     product_services      Product Services
     *
     *    Validate `product_services` is required
     *
     *    Validate `product_services` is array
     *
     *    Validate `product_services` contains only integer
     *
     *    Validate `product_services` value exist or not
     *
     *  @apiParam {Integer}     lead_conversion_id     Lead Conversion Id
     *
     *    Validate `lead_conversion_id` is required
     *
     *    Validate `lead_conversion_id` is integer
     *
     *    Validate `lead_conversion_id` is exists or not
     *
     * @apiParam {string}   [budget]    Budget
     *
     *    Validate `budget` is string
     *
     * @apiParam {string}   [time_line]    Timeline
     *
     *    Validate `time_line` is string
     *
     * @apiParam {string}   [description]    description
     *
     *    Validate `description` is string
     *
     * @apiParam {numeric}   [deal_amount]    Deal Amount
     *
     *    Validate `deal_amount` is numeric
     *
     *
     * @apiParam {string}   [win_close_reason]    Win Close Reason
     *
     *    Validate `win_close_reason` is string
     *
     * @apiParam {date}   [deal_close_date]    Deal Close Date
     *
     *    Validate `deal_close_date` is date
     *
     * @apiParam {array}   [documents]    Document
     *
     *    Validate `documents` is file* @apiParam {file}   [documents]    Document
     *
     *    Validate `documents` is array
     *
     *    Validate `documents` are file
     *
     *    Validate `documents` are less than equal to 5 MB
     *
     *    Validate `documents` file support jpg,jpeg,png,mp4,avi,mov,pdf,doc mimetype
     *
     * @apiParamExample {bodyJson} Request-Example:
     *    {
     *    "name": "Bhargav",
     *    "email": "bhargav960143@gmail.com",
     *    "country_code_alpha": "IN"
     *    "phone": "9662062016",
     *    "company_name": "Trentium",
     *    "company_size": "30",
     *    "company_website": "https://www.trentiums.com",
     *    "lead_status_id": 1,
     *    "lead_channel_id": 1,
     *    "product_services":[1,2],
     *    "lead_conversion_id": 1,
     *    "budget": "1500 INR",
     *    "time_line": "2 Hours",
     *    "description": "Banner development",
     *    "deal_amount": "1200",
     *    "win_close_reason": "",
     *    "deal_close_date": "2024-06-18",
     *    "documents": "Demo.jpg"
     *    }
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {String}    message                              Message for error & success
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/update-lead
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": true,
     *          "message": "Lead updated successfully."
     *      }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *          "status": false,
     *          "message": "Something wen't wrong please try again"
     *     }
     */
    public function update_lead(Request $request)
    {
        try {
            $userRequest = $request->all();
            $user = $request->user();
            $fields = [
                'lead_id' => [
                    'required',
                    'integer',
                    'exists:leads,id,deleted_at,NULL'
                ],
                'name' => [
                    'string',
                    'required',
                ],
                'email' => [
                    'required_without:phone',
                    'string',
                    'min:2',
                    'email:rfc,dns',
                ],
                'country_code_alpha' => [
                    'required_with:phone',
                    'string',
                    'exists:countries,country_code_alpha,deleted_at,NULL',
                ],
                'phone' => [
                    'required_without:email',
                    'required_with:country_code_alpha',
                    'numeric',
                    'phone:country_code_alpha',
                ],
                'company_name' => [
                    'string',
                    'nullable',
                ],
                'company_size' => [
                    'string',
                    'nullable',
                ],
                'company_website' => [
                    'string',
                    'nullable',
                ],
                'lead_status_id' => [
                    'required',
                    'exists:lead_statuses,id',
                    'integer',
                ],
                'lead_channel_id' => [
                    'required',
                    'exists:lead_channels,id',
                    'integer',
                ],
                'product_services.*' => [
                    'integer',
                    'exists:product_services,id,deleted_at,NULL'
                ],
                'product_services' => [
                    'required',
                    'array',
                ],
                'lead_conversion_id' => [
                    'required',
                    'exists:lead_conversions,id',
                    'integer',
                ],
                'budget' => [
                    'nullable',
                    'string'
                ],
                'description' => [
                    'nullable',
                    'string'
                ],
                'deal_amount' => [
                    'nullable',
                    'numeric'
                ],
                'time_line' => [
                    'string',
                    'nullable',
                ],
                'win_close_reason' => [
                    'string',
                    'nullable',
                ],
                'deal_close_date' => [
                    'date_format:' . config('panel.date_format'),
                    'nullable',
                ],
                'documents' => [
                    'nullable',
                    'array'
                ],
                'documents.*' => [
                    'required',
                    'file',
                    'max:' . config('settings.file_size.general'),
                    'mimes:' . config('settings.supported_file_extension.general'),
                ]
            ];

            $error = Validator::make($userRequest, $fields, [
                'name.required' => trans('label.lead_name_required_error_msg'),
                'name.string' => trans('label.lead_name_string_error_msg'),
                'email.required_without' => trans('label.email_required_error_msg'),
                'email.string' => trans('label.email_string_error_msg'),
                'email.email' => trans('label.email_format_error_msg'),
                'email.exists' => trans('label.email_exists_error_msg'),
                'country_code_alpha.required_with' => trans('label.lead_country_code_alpha_required_with_error_msg'),
                'country_code_alpha.string' => trans('label.lead_country_code_alpha_string_error_msg'),
                'country_code_alpha.exists' => trans('label.lead_country_code_alpha_exists_error_msg'),
                'phone.required_without' => trans('label.lead_phone_required_without_error_msg'),
                'phone.required_with' => trans('label.lead_phone_required_without_error_msg'),
                'phone.numeric' => trans('label.lead_phone_numeric_error_msg'),
                'phone.phone' => trans('label.lead_phone_phone_error_msg'),
                'company_name.string' => trans('label.company_name_string_error_msg'),
                'company_size.string' => trans('label.company_size_string_error_msg'),
                'company_website.string' => trans('label.company_website_string_error_msg'),
                'lead_status_id.required' => trans('label.lead_status_id_required_error_msg'),
                'lead_status_id.exists' => trans('label.lead_status_id_exists_error_msg'),
                'lead_status_id.integer' => trans('label.lead_status_id_integer_error_msg'),
                'lead_channel_id.required' => trans('label.lead_channel_id_required_error_msg'),
                'lead_channel_id.exists' => trans('label.lead_channel_id_exists_error_msg'),
                'lead_channel_id.integer' => trans('label.lead_channel_id_integer_error_msg'),
                'product_services.*.required' => trans('label.product_services_required_error_msg'),
                'product_services.*.exists' => trans('label.product_services_exists_error_msg'),
                'product_services.required' => trans('label.product_services_required_error_msg'),
                'product_services.array' => trans('label.product_services_array_error_msg'),
                'lead_conversion_id.required' => trans('label.lead_conversion_id_required_error_msg'),
                'lead_conversion_id.exists' => trans('label.lead_conversion_id_exists_error_msg'),
                'lead_conversion_id.integer' => trans('label.lead_conversion_id_integer_error_msg'),
                'budget.string' => trans('label.budget_string_error_msg'),
                'description.string' => trans('label.lead_description_string_error_msg'),
                'deal_amount.integer' => trans('label.lead_deal_amount_integer_error_msg'),
                'win_close_reason.string' => trans('label.win_close_reason_string_error_msg'),
                'time_line.string' => trans('label.time_line_string_error_msg'),
                'win_close_reason.string' => trans('label.win_close_reason_string_error_msg'),
                'deal_close_date.string' => trans('label.deal_close_date_string_error_msg'),
                'documents.array' => trans('label.documents_array_error_msg')
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Update Lead');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $companyUser = CompanyUser::where("user_id", "=", $user->id)->first();

            if (empty($companyUser)) {
                Auditable::log_audit_data('ProductServiceApiController@update_lead Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            if ($request->country_code_alpha) {
                $country = Country::where('country_code_alpha', $request->country_code_alpha)->first();
            }

            $lead = Lead::find($userRequest['lead_id']);

            $leadHistory = [];
            if ($lead->name != $userRequest['name']) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead name updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->phone != $request->phone) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead phone updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->email != $request->email) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead email updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->company_name != $request->company_name) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead company name updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->company_size != $request->company_size) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead company size updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->company_website != $request->company_website) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead company website updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->budget != $request->budget) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead budget updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->time_line != $request->time_line) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead time line updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->description != $request->description) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead description updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->deal_amount != $request->deal_amount) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead deal amount updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->win_close_reason != $request->win_close_reason) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead win close reason updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->deal_close_date != $request->deal_close_date) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead deal close date updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->lead_status_id != $userRequest['lead_status_id']) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead status updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->lead_channel_id != $userRequest['lead_channel_id']) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead channel updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            if ($lead->lead_conversion_id != $userRequest['lead_conversion_id']) {
                array_push($leadHistory, [
                    'lead_id' => $lead->id,
                    'company_user_id' => $companyUser->id,
                    'description' => 'Lead conversion updated',
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }

            if ($user->companyUser->company_id == $lead->company_user->company_id) {
                $lead->name = $userRequest['name'];
                $lead->phone = $userRequest['phone'] ?? null;
                $lead->email = $userRequest['email'] ?? null;
                $lead->company_name = $userRequest['company_name'] ?? null;
                $lead->company_size = $userRequest['company_size'] ?? null;
                $lead->company_website = $userRequest['company_website'] ?? null;
                $lead->lead_status_id = $userRequest['lead_status_id'];
                $lead->lead_channel_id = $userRequest['lead_channel_id'];
                $lead->lead_conversion_id = $userRequest['lead_conversion_id'];
                $lead->budget = $userRequest['budget'] ?? null;
                $lead->time_line = $userRequest['time_line'] ?? null;
                $lead->description = $userRequest['description'] ?? null;
                $lead->deal_amount = $userRequest['deal_amount'] ?? null;
                $lead->win_close_reason = $userRequest['win_close_reason'] ?? null;
                $lead->deal_close_date = $userRequest['deal_close_date'] ?? null;
                $lead->country_id = isset($country) ? $country->id : null;
                $lead->save();

                LeadHistory::insert($leadHistory);
                if (isset($userRequest['product_services']) && !empty($userRequest['product_services']) && !empty(array_diff($userRequest['product_services'], array_map('strval', $lead->product_services->pluck('id')->toArray())))) {
                    $lead->product_services()->detach();
                    $arrData = [];
                    foreach ($userRequest['product_services'] as $keyProduct => $valueProduct) {
                        $arrData[$keyProduct]['lead_id'] = $lead->id;
                        $arrData[$keyProduct]['product_service_id'] = $valueProduct;
                    }

                    LeadProductService::insert($arrData);

                    $leadHistory = new LeadHistory();
                    $leadHistory->lead_id = $lead->id;
                    $leadHistory->company_user_id = $companyUser->id;
                    $leadHistory->description = 'Lead product services updated';
                    $leadHistory->created_at = date("Y-m-d H:i:s");
                    $leadHistory->save();
                }

                if ($request->file('documents')) {
                    foreach ($request->file('documents') as $file) {
                        $lead->addMedia($file)->toMediaCollection('documents');
                    }

                    $leadHistory = new LeadHistory();
                    $leadHistory->lead_id = $lead->id;
                    $leadHistory->company_user_id = $companyUser->id;
                    $leadHistory->description = 'Lead documents uploaded';
                    $leadHistory->created_at = date("Y-m-d H:i:s");
                    $leadHistory->save();
                }

                return response()->json(['status' => true, 'message' => trans('label.lead_update_success_msg')], $this->successStatus);
            } else {
                Auditable::log_audit_data('ProductServiceApiController@update_lead Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('LeadApiController@update_lead Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {post} /api/v1/delete-lead Delete Lead
     * @apiSampleRequest off
     * @apiName Delete Lead
     * @apiGroup Lead
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Delete Lead API</span>
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
     *  @apiParam {Integer}     lead_id     Lead Id
     *
     *    Validate `lead_id` is required
     *
     *    Validate `lead_id` is integer
     *
     *    Validate `lead_id` is exists or not
     *
     * @apiParamExample {Json} Request-Example:
     *    {
     *          "lead_id": 1,
     *    }
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {String}    message                              Message for error & success
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/delete-lead
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": true,
     *          "message": "Lead deleted successfully."
     *      }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *          "status": false,
     *          "message": "Something wen't wrong please try again"
     *     }
     */
    public function delete_lead(Request $request)
    {
        try {
            $userRequest = $request->all();
            $user = $request->user();
            $fields = [
                'lead_id' => [
                    'required',
                    'integer',
                    'exists:leads,id,deleted_at,NULL'
                ],
            ];

            $error = Validator::make($userRequest, $fields, [
                'lead_id.required' => trans('label.lead_id_required_error_msg'),
                'lead_id.integer' => trans('label.lead_id_integer_error_msg'),
                'lead_id.exists' => trans('label.lead_id_exists_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Delete Lead');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $companyUser = CompanyUser::where("user_id", "=", $user->id)->first();

            if (empty($companyUser)) {
                Auditable::log_audit_data('ProductServiceApiController@delete_lead Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            $lead = Lead::find($userRequest['lead_id']);
            if ($user->companyUser->company_id == $lead->company_user->company_id) {
                $lead->product_services()->detach();
                if (!empty($lead->documents)) {
                    foreach ($lead->documents as $document) {
                        $document->delete();
                    }
                }
                $lead->delete();
                return response()->json(['status' => true, 'message' => trans('label.lead_delete_success_msg')], $this->successStatus);
            } else {
                Auditable::log_audit_data('ProductServiceApiController@delete_lead Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('LeadApiController@delete_lead Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {post} /api/v1/update-lead-status Update Lead Status
     * @apiSampleRequest off
     * @apiName Update Lead Status
     * @apiGroup Lead
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Update Lead Status API</span>
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
     *  @apiParam {integer}   type    Type
     *
     *    Validate `type` is required
     *
     *    Validate `type` is integer
     *
     *    Validate `type` must be from ['1' => 'status','2' => 'channel','3' => 'conversion']
     *
     *  @apiParam {Integer}     lead_status_id     Lead Status Id
     *
     *    Validate `lead_status_id` is required if type 1
     *
     *    Validate `lead_status_id` is integer
     *
     *    Validate `lead_status_id` is exists or not
     *
     *  @apiParam {Integer}     lead_channel_id     Lead Channel Id
     *
     *    Validate `lead_channel_id` is required if type 2
     *
     *    Validate `lead_channel_id` is integer
     *
     *    Validate `lead_channel_id` is exists or not
     *
     *  @apiParam {Integer}     lead_conversion_id     Lead Conversion Id
     *
     *    Validate `lead_conversion_id` is required if type 3
     *
     *    Validate `lead_conversion_id` is integer
     *
     *    Validate `lead_conversion_id` is exists or not
     *
     *  @apiParam {Integer}     lead_id     Lead Id
     *
     *    Validate `lead_id` is required
     *
     *    Validate `lead_id` is integer
     *
     *    Validate `lead_id` is exists or not
     *
     * @apiParamExample {Json} Request-Example:
     *    {
     *          "type" : 1,
     *          "lead_status_id" : 2,
     *          "lead_id": 1,
     *    }
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {String}    message                              Message for error & success
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/update-lead-status
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": true,
     *          "message": "Lead status updated successfully."
     *      }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *          "status": false,
     *          "message": "Something wen't wrong please try again"
     *     }
     */
    public function update_lead_status(Request $request)
    {
        try {
            $userRequest = $request->all();
            $user = $request->user();
            $fields = [
                'type' => [
                    'required',
                    'integer',
                    'in:' . implode(',', array_keys(Lead::STATUS_UPDATE_TYPE))
                ],
                'lead_status_id' => [
                    'required_if:type,' . array_flip(Lead::STATUS_UPDATE_TYPE)['status'],
                    'exists:lead_statuses,id,deleted_at,NULL',
                    'integer',
                ],
                'lead_channel_id' => [
                    'required_if:type,' . array_flip(Lead::STATUS_UPDATE_TYPE)['channel'],
                    'exists:lead_channels,id,deleted_at,NULL',
                    'integer',
                ],
                'lead_conversion_id' => [
                    'required_if:type,' . array_flip(Lead::STATUS_UPDATE_TYPE)['conversion'],
                    'exists:lead_conversions,id,deleted_at,NULL',
                    'integer',
                ],
                'lead_id' => [
                    'required',
                    'integer',
                    'exists:leads,id,deleted_at,NULL'
                ],
            ];

            $error = Validator::make($userRequest, $fields, [
                'lead_status_id.required_if' => trans('label.lead_status_id_required_error_msg'),
                'lead_status_id.exists' => trans('label.lead_status_id_exists_error_msg'),
                'lead_status_id.integer' => trans('label.lead_status_id_integer_error_msg'),
                'lead_channel_id.required_if' => trans('label.lead_channel_id_required_error_msg'),
                'lead_channel_id.exists' => trans('label.lead_channel_id_exists_error_msg'),
                'lead_channel_id.integer' => trans('label.lead_channel_id_integer_error_msg'),
                'lead_conversion_id.required_if' => trans('label.lead_conversion_id_required_error_msg'),
                'lead_conversion_id.exists' => trans('label.lead_conversion_id_exists_error_msg'),
                'lead_conversion_id.integer' => trans('label.lead_conversion_id_integer_error_msg'),
                'lead_id.required' => trans('label.lead_id_required_error_msg'),
                'lead_id.integer' => trans('label.lead_id_integer_error_msg'),
                'lead_id.exists' => trans('label.lead_id_exists_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Update Lead Status');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $companyUser = CompanyUser::where("user_id", "=", $user->id)->first();

            if (empty($companyUser)) {
                Auditable::log_audit_data('ProductServiceApiController@update_lead_status Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            $lead = Lead::find($userRequest['lead_id']);
            if ($user->companyUser->company_id == $lead->company_user->company_id) {
                if ($userRequest['type'] == array_flip(Lead::STATUS_UPDATE_TYPE)['status']) {
                    $old_lead_status = $lead->lead_status->name;
                    $lead->update([
                        'lead_status_id' => $userRequest['lead_status_id']
                    ]);
                    $lead->refresh();
                    $description = "Lead status updated from " . $old_lead_status . " to " . $lead->lead_status->name;
                } else if ($userRequest['type'] == array_flip(Lead::STATUS_UPDATE_TYPE)['channel']) {
                    $old_lead_channel = $lead->lead_channel->name;
                    $lead->update([
                        'lead_channel_id' => $userRequest['lead_channel_id']
                    ]);
                    $lead->refresh();
                    $description = "Lead channel updated from " . $old_lead_channel . " to " . $lead->lead_channel->name;
                } else if ($userRequest['type'] == array_flip(Lead::STATUS_UPDATE_TYPE)['conversion']) {
                    $old_lead_conversion = $lead->lead_conversion->name;
                    $lead->update([
                        'lead_conversion_id' => $userRequest['lead_conversion_id']
                    ]);
                    $lead->refresh();
                    $description = "Lead conversion updated from " . $old_lead_conversion . " to " . $lead->lead_conversion->name;
                }

                $leadHistory = new LeadHistory();
                $leadHistory->lead_id = $lead->id;
                $leadHistory->company_user_id = $companyUser->id;
                $leadHistory->description = $description;
                $leadHistory->created_at = date("Y-m-d H:i:s");
                $leadHistory->save();
                return response()->json(['status' => true, 'message' => trans('label.lead_status_update_success_msg')], $this->successStatus);
            } else {
                Auditable::log_audit_data('LeadApiController@update_lead_status Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('LeadApiController@update_lead_status Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {post} /api/v1/delete-lead-document Delete Lead Document
     * @apiSampleRequest off
     * @apiName Delete Lead Document
     * @apiGroup Lead
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Delete Lead Document API</span>
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
     *  @apiParam {Integer}     media_id     Lead Id
     *
     *    Validate `media_id` is required
     *
     *    Validate `media_id` is integer
     *
     *    Validate `media_id` is exists or not
     *
     * @apiParamExample {Json} Request-Example:
     *    {
     *          "media_id": 1,
     *    }
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {String}    message                              Message for error & success
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/delete-lead-document
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": true,
     *          "message": "Media deleted successfully."
     *      }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *          "status": false,
     *          "message": "Something wen't wrong please try again"
     *     }
     */
    public function delete_lead_document(Request $request)
    {
        try {
            $userRequest = $request->all();
            $user = $request->user();
            $fields = [
                'media_id' => [
                    'required',
                    'integer',
                    'exists:media,id'
                ],
            ];

            $error = Validator::make($userRequest, $fields, [
                'media_id.required' => trans('label.media_id_required_error_msg'),
                'media_id.integer' => trans('label.media_id_integer_error_msg'),
                'media_id.exists' => trans('label.media_id_exists_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Delete Lead Document');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $media = Media::find($userRequest['media_id']);

            if ($media->model_type == "App\Models\Lead") {
                $companyUser = CompanyUser::where("user_id", "=", $user->id)->first();

                if (empty($companyUser)) {
                    Auditable::log_audit_data('ProductServiceApiController@delete_lead_document Exception', $user, config('settings.log_type')[1], $userRequest);
                    return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
                }

                $lead = $media->model;
                if ($user->companyUser->company_id == $lead->company_user->company_id) {
                    $media->delete();
                    return response()->json(['status' => true, 'message' => trans('label.media_deleted_success_msg')], $this->successStatus);
                } else {
                    Auditable::log_audit_data('LeadApiController@delete_lead_document Exception', $user, config('settings.log_type')[1], $userRequest);
                    return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
                }
            } else{
                Auditable::log_audit_data('LeadApiController@delete_lead_document Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('LeadApiController@delete_lead_document Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {get} /api/v1/lead-details Lead Details
     * @apiSampleRequest off
     * @apiName Lead Details
     * @apiGroup Lead
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__get">Lead Details API</span>
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
     *  @apiParam {Integer}     lead_id     Lead Id
     *
     *    Validate `lead_id` is required
     *
     *    Validate `lead_id` is integer
     *
     *    Validate `lead_id` is exists or not
     *
     * @apiParamExample {Json} Request-Example:
     *    {
     *          "lead_id": 1,
     *    }
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {String}    message                              Message for error & success
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/lead-details
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": true,
    *            "data": {
    *               "id": 3,
    *               "company_user_id": 1,
    *               "name": "Bhargav",
    *               "email": "bhargav960143@gmail.com",
    *               "phone": "9662062016",
    *               "company_name": "Trentium",
    *               "company_size": "30",
    *               "company_website": "https://www.trentiums.com",
    *               "budget": "1500 INR",
    *               "time_line": "2 Hours",
    *               "description": "Banner development",
    *               "deal_amount": 1200,
    *               "win_close_reason": null,
    *               "deal_close_date": "2024-06-18",
    *               "created_at": "2024-06-19 07:24:43",
    *               "updated_at": "2024-06-26 11:10:52",
    *               "deleted_at": null,
    *               "lead_status_id": 2,
    *               "lead_channel_id": 1,
    *               "lead_conversion_id": 3,
    *               "country_id": null,
    *               "documents": [],
    *               "lead_status": {
    *                   "id": 2,
    *                   "name": "Contacted",
    *                   "created_at": "2024-06-04 10:04:45",
    *                   "updated_at": "2024-06-04 10:04:45",
    *                   "deleted_at": null
    *               },
    *               "lead_channel": {
    *                   "id": 1,
    *                   "name": "Website Forms",
    *                   "created_at": "2024-06-04 10:02:53",
    *                   "updated_at": "2024-06-04 10:02:53",
    *                   "deleted_at": null
    *               },
    *               "product_services": [
    *                   {
    *                       "id": 1,
    *                       "company_user_id": 1,
    *                       "name": "sdgsdg123",
    *                       "description": "etwerweyt",
    *                       "created_at": "2024-06-11 11:33:24",
    *                       "updated_at": "2024-06-19 07:43:32",
    *                       "deleted_at": null,
    *                       "documents": null,
    *                       "pivot": {
    *                           "lead_id": 3,
    *                           "product_service_id": 1
    *                       },
    *                       "media": []
    *                   },
    *                   {
    *                       "id": 2,
    *                       "company_user_id": 3,
    *                       "name": "sdgsdg12",
    *                       "description": "etwerweyt",
    *                       "created_at": "2024-06-18 06:26:25",
    *                       "updated_at": "2024-06-19 07:43:32",
    *                       "deleted_at": null,
    *                       "documents": {
    *                           "id": 10,
    *                           "model_type": "App\\Models\\ProductService",
    *                           "model_id": 2,
    *                           "uuid": "90a1f490-1bc6-4370-9bb1-e9ec1b533810",
    *                           "collection_name": "documents",
    *                           "name": "user-form",
    *                           "file_name": "user-form.png",
    *                           "mime_type": "image/png",
    *                           "disk": "public",
    *                           "conversions_disk": "public",
    *                           "size": 9013,
    *                           "manipulations": [],
    *                           "custom_properties": [],
    *                           "generated_conversions": {
    *                               "thumb": true,
    *                               "preview": true
    *                           },
    *                           "responsive_images": [],
    *                           "order_column": 1,
    *                           "created_at": "2024-06-19T06:08:17.000000Z",
    *                           "updated_at": "2024-06-19T06:08:18.000000Z",
    *                           "original_url": "http://127.0.0.1:8000/storage/10/user-form.png",
    *                           "preview_url": "http://127.0.0.1:8000/storage/10/conversions/user-form-preview.jpg"
    *                       },
    *                       "pivot": {
    *                           "lead_id": 3,
    *                           "product_service_id": 2
    *                       },
    *                       "media": [
    *                           {
    *                               "id": 10,
    *                               "model_type": "App\\Models\\ProductService",
    *                               "model_id": 2,
    *                               "uuid": "90a1f490-1bc6-4370-9bb1-e9ec1b533810",
    *                               "collection_name": "documents",
    *                               "name": "user-form",
    *                               "file_name": "user-form.png",
    *                               "mime_type": "image/png",
    *                               "disk": "public",
    *                               "conversions_disk": "public",
    *                               "size": 9013,
    *                               "manipulations": [],
    *                               "custom_properties": [],
    *                               "generated_conversions": {
    *                                   "thumb": true,
    *                                   "preview": true
    *                               },
    *                               "responsive_images": [],
    *                               "order_column": 1,
    *                               "created_at": "2024-06-19T06:08:17.000000Z",
    *                               "updated_at": "2024-06-19T06:08:18.000000Z",
    *                               "original_url": "http://127.0.0.1:8000/storage/10/user-form.png",
    *                               "preview_url": "http://127.0.0.1:8000/storage/10/conversions/user-form-preview.jpg"
    *                           }
    *                       ]
    *                   }
    *               ],
    *               "lead_conversion": {
    *                   "id": 3,
    *                   "name": "Negotiation",
    *                   "created_at": "2024-06-04 10:05:47",
    *                   "updated_at": "2024-06-04 10:05:47",
    *                   "deleted_at": null
    *               },
    *               "company_user": {
    *                   "id": 1,
    *                   "created_at": "2024-06-04 15:47:00",
    *                   "updated_at": "2024-06-27 06:33:12",
    *                   "deleted_at": null,
    *                   "company_id": 1,
    *                   "user_id": 2,
    *                   "user": {
    *                       "id": 2,
    *                       "name": "Trentium Solution Private Limited",
    *                       "email": "info@trentiums.com",
    *                       "email_verified_at": null,
    *                       "user_role": 2,
    *                       "created_at": "2024-06-04 10:07:02",
    *                       "updated_at": "2024-06-27 06:33:12",
    *                       "deleted_at": null
    *                   }
    *               },
    *               "media": []
    *            }
     *      }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *          "status": false,
     *          "message": "Something wen't wrong please try again"
     *     }
     */
    public function lead_details(Request $request)
    {
        try {
            $userRequest = $request->all();
            $user = $request->user();
            $fields = [
                'lead_id' => [
                    'required',
                    'integer',
                    'exists:leads,id,deleted_at,NULL'
                ],
            ];

            $error = Validator::make($userRequest, $fields, [
                'lead_id.required' => trans('label.lead_id_required_error_msg'),
                'lead_id.integer' => trans('label.lead_id_integer_error_msg'),
                'lead_id.exists' => trans('label.lead_id_exists_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Delete Lead');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $companyUser = CompanyUser::where("user_id", "=", $user->id)->first();

            if (empty($companyUser)) {
                Auditable::log_audit_data('ProductServiceApiController@lead_details Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }

            $lead = Lead::with(['lead_status', 'lead_channel', 'product_services', 'lead_conversion', 'company_user.user'])->findOrFail($userRequest['lead_id']);
            if ($user->companyUser->company_id == $lead->company_user->company_id) {

                $leadHistory = new LeadHistory();
                $leadHistory->lead_id = $lead->id;
                $leadHistory->company_user_id = $companyUser->id;
                $leadHistory->description = $user->name.' show lead '.$lead->id;
                $leadHistory->created_at = date("Y-m-d H:i:s");
                $leadHistory->save();

                return response()->json(['status' => true, 'data' => $lead], $this->successStatus);
            } else {
                Auditable::log_audit_data('ProductServiceApiController@lead_details Exception', $user, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('LeadApiController@lead_details Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
}
