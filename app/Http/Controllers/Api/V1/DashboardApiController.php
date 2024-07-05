<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\Auditable;
use App\Models\Lead;
use App\Models\LeadConversion;
use Illuminate\Http\Request;
use App\Traits\Validation;
use Exception;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    use Validation;
    public $successStatus = 200;

    /**
     * @api {get} /api/v1/lead-stage-count Lead Stage Count
     * @apiSampleRequest off
     * @apiName Lead Stage Count
     * @apiGroup Dashboard
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Lead Stage Count API</span>
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
     * @apiSuccess {Boolean}   status                            Response successful or not
     * @apiSuccess {Object}    data                              Lead stage counts
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/lead-stage-count
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": true,
     *         "data": [
     *            {
     *                "lead_conversion_id": 1,
     *                "name": "Initial",
     *                "lead_count": 23
     *            },
     *            {
     *                "lead_conversion_id": 2,
     *                "name": "Proposal Stage",
     *                "lead_count": 0
     *            },
     *            {
     *                "lead_conversion_id": 3,
     *                "name": "Negotiation",
     *                "lead_count": 2
     *            },
     *            {
     *                "lead_conversion_id": 4,
     *                "name": "Closed-Won",
     *                "lead_count": 0
     *            },
     *            {
     *                "lead_conversion_id": 5,
     *                "name": "Closed-Lost",
     *                "lead_count": 0
     *            }
     *         ]
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *          "status": false,
     *          "message": "Something wen't wrong please try again"
     *     }
     */
    public function lead_stage_count(Request $request)
    {
        try {
            $user = $request->user();
            $userRequest = $request->all();

            if (isset($user->companyUser) && !empty($user->companyUser)) {
                $response = LeadConversion::select('lead_conversions.id as lead_conversion_id', 'lead_conversions.name', DB::raw('COUNT(tmp.id) AS lead_count'))
                ->leftJoinSub(
                    Lead::join('company_users', 'company_users.id', '=', 'leads.company_user_id')
                        ->where('company_users.company_id', $user->companyUser->company_id)
                        ->whereNull('company_users.deleted_at')
                        ->select('leads.*'),
                    'tmp',
                    'tmp.lead_conversion_id',
                    '=',
                    'lead_conversions.id'
                )
                ->groupBy('lead_conversions.id', 'lead_conversions.name')
                ->orderBy('lead_conversion_id','ASC')
                ->get();
                return response()->json(['status' => true, 'data' => $response], $this->successStatus);
            } else {
                Auditable::log_audit_data('DashboardApiController@lead_stage_count Company not found', null, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('DashboardApiController@lead_stage_count Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

    /**
     * @api {get} /api/v1/dashboard-lead-list Dashboard Lead List
     * @apiSampleRequest off
     * @apiName Dashboard Lead List
     * @apiGroup Dashboard
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Dashboard Lead List API</span>
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
     * @apiParam {integer}     [page]    Page No
     *
     *   Validate `page` is integer
     *
     * @apiParamExample {queryParam} Request-Example:
     *    {
     *           "page" : 2
     *    }
     *
     * @apiSuccess {Boolean}   status                            Response successful or not
     * @apiSuccess {Object}    data                              Lead List
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/dashboard-lead-list
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "status": true,
     *         "data": {
     *             "current_page": 1,
     *             "data": [
     *                 {
     *                     "name": "Bhargav123",
     *                     "phone": null,
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 07:18:14",
     *                     "documents": null,
     *                     "media": []
     *                 },
     *                 {
     *                     "name": "Bhargav",
     *                     "phone": "9662062016",
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 07:24:43",
     *                     "documents": null,
     *                     "media": []
     *                 },
     *                 {
     *                     "name": "Bhargav123",
     *                     "phone": "9662062016",
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 09:20:15",
     *                     "documents": null,
     *                     "media": []
     *                 },
     *                 {
     *                     "name": "Bhargav123",
     *                     "phone": "9662062016",
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 09:20:32",
     *                     "documents": null,
     *                     "media": []
     *                 },
     *                 {
     *                     "name": "Bhargav123",
     *                     "phone": "9662062016",
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 09:21:48",
     *                     "documents": null,
     *                     "media": []
     *                 },
     *                 {
     *                     "name": "Bhargav123",
     *                     "phone": "9662062016",
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 09:22:00",
     *                     "documents": null,
     *                     "media": []
     *                 },
     *                 {
     *                     "name": "Bhargav123",
     *                     "phone": "9662062016",
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 09:22:30",
     *                     "documents": null,
     *                     "media": []
     *                 },
     *                 {
     *                     "name": "Bhargav123",
     *                     "phone": "9662062016",
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 09:22:37",
     *                     "documents": null,
     *                     "media": []
     *                 },
     *                 {
     *                     "name": "Bhargav123",
     *                     "phone": "9662062016",
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 09:24:34",
     *                     "documents": null,
     *                     "media": []
     *                 },
     *                 {
     *                     "name": "Bhargav123",
     *                     "phone": "9662062016",
     *                     "email": "bhargav960143@gmail.com",
     *                     "created_at": "2024-06-19 09:28:11",
     *                     "documents": null,
     *                     "media": []
     *                 }
     *             ],
     *             "first_page_url": "http://127.0.0.1:8000/api/v1/dashboard-lead-list?page=1",
     *             "from": 1,
     *             "last_page": 2,
     *             "last_page_url": "http://127.0.0.1:8000/api/v1/dashboard-lead-list?page=2",
     *             "links": [
     *                 {
     *                     "url": null,
     *                     "label": "&laquo; Previous",
     *                     "active": false
     *                 },
     *                 {
     *                     "url": "http://127.0.0.1:8000/api/v1/dashboard-lead-list?page=1",
     *                     "label": "1",
     *                     "active": true
     *                 },
     *                 {
     *                     "url": "http://127.0.0.1:8000/api/v1/dashboard-lead-list?page=2",
     *                     "label": "2",
     *                     "active": false
     *                 },
     *                 {
     *                     "url": "http://127.0.0.1:8000/api/v1/dashboard-lead-list?page=2",
     *                     "label": "Next &raquo;",
     *                     "active": false
     *                 }
     *             ],
     *             "next_page_url": "http://127.0.0.1:8000/api/v1/dashboard-lead-list?page=2",
     *             "path": "http://127.0.0.1:8000/api/v1/dashboard-lead-list",
     *             "per_page": 10,
     *             "prev_page_url": null,
     *             "to": 10,
     *             "total": 16
     *         }
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *          "status": false,
     *          "message": "Something wen't wrong please try again"
     *     }
     */
    public function dashboard_lead_list(Request $request)
    {
        try {
            $user = $request->user();
            $userRequest = $request->all();

            if (isset($user->companyUser) && !empty($user->companyUser)) {
                $leadList = Lead::select('id','name', 'phone', 'email', 'created_at','country_id')->with(['country' => function($query) {
                    $query->select('id', 'dialling_code');
                }])
                ->whereHas('company_user', function ($query) use ($user) {
                    $query->where('company_id', $user->companyUser->company_id);
                })->whereHas('lead_status', function ($query) {
                    $query->where('name', 'NEW');
                })->whereHas('lead_conversion', function ($query) {
                    $query->where('name', 'Initial');
                })->orderBy('id','DESC')->paginate(10);

                return response()->json(['status' => true, 'data' => $leadList], $this->successStatus);
            } else {
                Auditable::log_audit_data('DashboardApiController@dashboard_lead_list Company not found', null, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('DashboardApiController@dashboard_lead_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
}
