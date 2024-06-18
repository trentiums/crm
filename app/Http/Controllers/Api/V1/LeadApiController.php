<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\CompanyUser;
use App\Models\Lead;
use App\Models\LeadProductService;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use Exception;
use DB;
use Hash;
use App\Traits\Validation;
use Illuminate\Support\Facades\Validator;

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
     *       curl -i https://crm.torthelp.com/api/v1/lead-list
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *          "status": true,
     *          "data": {
     *              "current_page": 1,
     *              "data": [],
     *              "first_page_url": "https://crm.torthelp.com/api/v1/lead-list?page=1",
     *              "from": null,
     *              "last_page": 1,
     *              "last_page_url": "https://crm.torthelp.com/api/v1/lead-list?page=1",
     *              "links": [
     *                  {
     *                      "url": null,
     *                      "label": "&laquo; Previous",
     *                      "active": false
     *                  },
     *                  {
     *                      "url": "https://crm.torthelp.com/api/v1/lead-list?page=1",
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
     *              "path": "https://crm.torthelp.com/api/v1/lead-list",
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
    public function lead_list(Request $request){
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

            $leadConversion = Lead::with(['lead_status','lead_channel','product_services','lead_conversion'])
                ->join('company_users','company_users.id',"=","leads.company_user_id")
                ->join('users','users.id',"=","company_users.user_id")
                ->where("company_users.user_id","=",$user->id);

            if(isset($userRequest['start_date']) && !empty($userRequest['start_date']) && isset($userRequest['end_date']) && !empty($userRequest['end_date'])){
                $leadConversion->whereDate('leads.created_at', ">=", $userRequest['start_date']);
                $leadConversion->whereDate('leads.created_at', "<=", $userRequest['end_date']);
            }

            if(isset($userRequest['lead_status_id']) && !empty($userRequest['lead_status_id'])){
                $leadConversion->where('leads.lead_status_id',$userRequest['lead_status_id']);
            }

            if(isset($userRequest['lead_channel_id']) && !empty($userRequest['lead_channel_id'])){
                $leadConversion->where('leads.lead_channel_id',$userRequest['lead_channel_id']);
            }

            if(isset($userRequest['lead_conversion_id']) && !empty($userRequest['lead_conversion_id'])){
                $leadConversion->where('leads.lead_conversion_id',$userRequest['lead_conversion_id']);
            }

            if(isset($userRequest['order_by']) && !empty($userRequest['order_by']) && isset($userRequest['sort_order']) && !empty($userRequest['sort_order'])){
                if($userRequest['order_by'] == array_flip(Lead::ORDER_BY)['created_at']){
                    $order_by = "leads.created_at";
                }
                else if($userRequest['order_by'] == array_flip(Lead::ORDER_BY)['name']){
                    $order_by = "leads.name";
                }
                else if($userRequest['order_by'] == array_flip(Lead::ORDER_BY)['email']){
                    $order_by = "leads.email";
                }
                else if($userRequest['order_by'] == array_flip(Lead::ORDER_BY)['company_user_id']){
                    $order_by = "users.name";
                }
                $leadConversion->orderBy($order_by,$userRequest['sort_order']);
            }

            $leadConversion = $leadConversion->paginate(10);

            return response()->json(['status' => true, 'data' => $leadConversion], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('LeadApiController@lead_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    public function save_lead(Request $request){
        try {
            $userRequest = $request->all();
            $user = $request->user();
            $fields = [
                'name' => [
                    'string',
                    'required',
                ],
                'email' => [
                    'nullable',
                    'string',
                    'min:2',
                    'email:rfc,dns',
                    'exists:users,email',
                ],
                'phone' => [
                    'string',
                    'nullable',
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
                    'exists:product_services,id'
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
            ];

            $error = Validator::make($userRequest, $fields, [
                'name.required' => trans('label.lead_name_required_error_msg'),
                'name.string' => trans('label.lead_name_string_error_msg'),
                'email.string' => trans('label.email_string_error_msg'),
                'email.email' => trans('label.email_format_error_msg'),
                'email.exists' => trans('label.email_exists_error_msg'),
                'phone.string' => trans('label.lead_phone_string_error_msg'),
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
                'time_line.string' => trans('label.time_line_string_error_msg'),
                'win_close_reason.string' => trans('label.win_close_reason_string_error_msg'),
                'deal_close_date.string' => trans('label.deal_close_date_string_error_msg')
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Lead List');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $companyUser = CompanyUser::where("user_id","=",$user->id)->first();

            $lead = new Lead();
            $lead->company_user_id = $companyUser->id;
            $lead->name = $userRequest['name'];
            $lead->phone = $userRequest['phone'];
            $lead->email = $userRequest['email'];
            $lead->company_name = $userRequest['company_name'];
            $lead->company_size = $userRequest['company_size'];
            $lead->company_website = $userRequest['company_website'];
            $lead->lead_status_id = $userRequest['lead_status_id'];
            $lead->lead_channel_id = $userRequest['lead_channel_id'];
            $lead->lead_conversion_id = $userRequest['lead_conversion_id'];
            $lead->budget = $userRequest['budget'];
            $lead->time_line = $userRequest['time_line'];
            $lead->description = $userRequest['description'];
            $lead->deal_amount = $userRequest['deal_amount'];
            $lead->win_close_reason = $userRequest['email'];
            $lead->deal_close_date = $userRequest['email'];
            $lead->save();

            if(isset($userRequest['product_services']) && !empty($userRequest['product_services'])){
                $arrData = [];
                foreach($userRequest['product_services'] as $keyProduct => $valueProduct){
                    $arrData[$keyProduct]['lead_id'] = $lead->id;
                    $arrData[$keyProduct]['product_service_id'] = $valueProduct;
                }

                LeadProductService::insert($arrData);
            }

            return response()->json(['status' => true, 'message' => trans('label.lead_insert_success_msg')], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('LeadApiController@save_lead Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
}
