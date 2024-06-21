<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\Auditable;
use App\Models\Lead;
use App\Models\LeadConversionCountView;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Traits\Validation;
use Exception;

class DashboardApiController extends Controller
{
    use Validation;
    public $successStatus = 200;

    private function getConversionCount($user, $stage)
    {
        $query = LeadConversionCountView::whereHas('lead_conversion', function ($query) use ($stage) {
            $query->where('name', $stage);
        });

        $query->whereHas('company_user', function ($query) use ($user) {
            $query->where('company_id', $user->companyUser->company_id);
        });
        return $query->sum('conversion_count');
    }

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
     *         "data": {
     *             "initial_count": "17",
     *             "proposal stage_count": 0,
     *             "negotiation_count": "1",
     *             "closed_won_count": 0,
     *             "closed_lost_count": 0
     *         }
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
            $stages = ['Initial', 'Proposal Stage', 'Negotiation', 'Closed-Won', 'Closed-Lost'];

            $response = [];

            foreach ($stages as $stage) {
                $stageKey = strtolower(str_replace('-', '_', $stage)) . '_count';
                $response[$stageKey] = $this->getConversionCount($user, $stage);
            }

            return response()->json(['status' => true, 'data' => $response], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('CompanyUserApiController@company_user_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
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

            $leadList = Lead::select('name', 'phone', 'email', 'created_at')->whereHas('company_user', function ($query) use ($user) {
                $query->where('company_id', $user->companyUser->company_id);
            })->whereHas('lead_status', function ($query) {
                $query->where('name', 'NEW');
            })->whereHas('lead_conversion', function ($query) {
                $query->where('name', 'Initial');
            })->paginate(10);

            return response()->json(['status' => true, 'data' => $leadList], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('CompanyUserApiController@company_user_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
}