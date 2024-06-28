<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\Validation;
use Exception;
use Illuminate\Http\Request;
use App\Traits\Auditable;
use Illuminate\Support\Facades\Cache;

class SettingApiController extends Controller
{
    use Validation;

    public $successStatus = 200;

    /**
     * @api {get} /api/v1/setting-list Setting List
     * @apiSampleRequest off
     * @apiName Setting List
     * @apiGroup CMS
     * @apiVersion 1.0.0
     *
     * @apiDescription API request content-type [{"key":"Content-Type","value":"application/json"}]
     *
     * @apiDescription API response json
     *
     * @apiSuccess {Boolean}    status                                  Response successful or not
     * @apiSuccess {String}     message                                 Message for success or error
     * @apiSuccess {Array}      data                                    Setting Data
     *
     * @apiExample {curl} Example usage:
     *     curl -i http://crm.trentiums.com/api/v1/setting-list
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "data": {
     *           "log_type": [
     *               "ERROR",
     *               "API"
     *           ],
     *           "date_format": "Y-m-d",
     *           "supported_file_format": {
     *               "general": "image/jpg,image/jpeg,image/png,video/mp4,video/avi,application/octet-stream,video/quicktime",
     *               "image": "image/jpg,image/jpeg,image/png",
     *               "icon": "image/jpg,image/jpeg,image/png",
     *               "video": "video/mp4,video/avi,application/octet-stream,video/quicktime"
     *           },
     *           "file_size": {
     *               "general": 5120,
     *               "image": 5120,
     *               "icon": 2048,
     *               "video": 512000
     *           },
     *           "supported_file_extension": {
     *               "general": "jpg,jpeg,png,mp4,avi,mov,pdf,doc",
     *               "icon": "jpg,jpeg,png",
     *               "video": "mp4,avi,mov"
     *           },
     *           "cache_data_limit": {
     *               "seconds": 86400,
     *               "days": 365
     *           }
     *        }
     *     }
     *
     * @apiError ServerFault        Something wen't wrong please try again.
     *
     * @apiErrorExample Error-Response:
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     *
     */
    public function settings_list(){
        try {
            $settingList = Cache::remember('settings_list', config('settings.cache_data_limit')['seconds'] * config('settings.cache_data_limit')['days'], function () {
                return config('settings');
            });
            return response()->json(['status' => true, 'data' => $settingList], $this->successStatus);
        }
        catch (Exception $ex) {
            Auditable::log_audit_data('SettingApiController@settings_list Exception',null,config('settings.log_type')[0],$ex->getMessage());
            return response()->json(['status' => false,'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
}
