<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\LeadChannel;
use App\Models\LeadConversion;
use App\Models\LeadStatus;
use App\Models\ProductService;
use App\Models\Role;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use Exception;
use DB;
use Hash;
use App\Traits\Validation;

class CmsApiController extends Controller
{
    use Validation;

    public $successStatus = 200;

    /**
     * @api {get} /api/v1/lead-channel-list Lead Channel List
     * @apiSampleRequest off
     * @apiName Lead Channel List
     * @apiGroup CMS
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Lead Channel List API</span>
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
     * @apiSuccess {Array}    data                                  Lead Channel List
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/lead-channel-list
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "status": true,
     *      "data": [{
     *          "id": 1,
     *          "name": "Website Forms",
     *          "created_at": "2024-06-04 10:02:53",
     *          "updated_at": "2024-06-04 10:02:53",
     *          "deleted_at": null
     *      }]
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     */
    public function lead_channel_list(Request $request)
    {
        try {
            $leadChannel = LeadChannel::all();
            return response()->json(['status' => true, 'data' => $leadChannel], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('CmsApiController@lead_channel_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
    /**
     * @api {get} /api/v1/lead-status-list Lead Status List
     * @apiSampleRequest off
     * @apiName Lead Status List
     * @apiGroup CMS
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Lead Status List API</span>
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
     * @apiSuccess {Array}    data                                  Lead Status List
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/lead-status-list
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "status": true,
     *      "data": [{
     *          "id": 1,
     *          "name": "New",
     *          "created_at": "2024-06-04 10:02:53",
     *          "updated_at": "2024-06-04 10:02:53",
     *          "deleted_at": null
     *      }]
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     */
    public function lead_status_list(Request $request)
    {
        try {
            $leadStatus = LeadStatus::all();
            return response()->json(['status' => true, 'data' => $leadStatus], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('CmsApiController@lead_status_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
    /**
     * @api {get} /api/v1/lead-conversion-list Lead Conversion List
     * @apiSampleRequest off
     * @apiName Lead Conversion List
     * @apiGroup CMS
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Lead Conversion List API</span>
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
     * @apiSuccess {Array}    data                                  Lead Conversion List
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/lead-conversion-list
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "status": true,
     *      "data": [{
     *          "id": 1,
     *          "name": "Proposal Stage",
     *          "created_at": "2024-06-04 10:02:53",
     *          "updated_at": "2024-06-04 10:02:53",
     *          "deleted_at": null
     *      }]
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     */
    public function lead_conversion_list(Request $request)
    {
        try {
            $leadConversion = LeadConversion::orderBy('id','ASC')->get();
            return response()->json(['status' => true, 'data' => $leadConversion], $this->successStatus);
        } catch (Exception $ex) {
            Auditable::log_audit_data('CmsApiController@lead_conversion_list Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }

}
