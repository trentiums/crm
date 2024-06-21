<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Exception;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use App\Traits\Validation;
use Illuminate\Support\Facades\Cache;

class CountryApiController extends Controller
{
    use Validation;
    public $successStatus = 200;

     /**
     *   @api {get} /api/v1/country-list Country List
     *   @apiSampleRequest off
     *   @apiName Country List
     *   @apiGroup CMS
     *   @apiVersion 1.0.0
     *
     *   @apiDescription <span class="type type__post">Country List API</span>
     *
     *   API request content-type [{"key":"Content-Type","value":"application/json"}]
     *
     *   @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/country-list
     *
     *   @apiSuccess {Boolean}   status                               Response successful or not
     *   @apiSuccess {String}    message                              Message for error & success
     *   @apiSuccess {object}    data                                 Country List
     *
     *   @apiSuccessExample Success-Response:
     *    HTTP/1.1 200 OK
     *     {
     *     "status": true,
     *     "data": [
     *              {
     *                   "id": 1,
     *                   "full_name" : "Canada"
     *                   "dialling_code": "1",
     *                   "country_code_alpha": "CA",
     *                   "flag": "https://media-4.api-sports.io/flags/ca.svg"
     *              }
     *          ]
     *      }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     *
     */
    public function country_list(){
        try {
            $countryList = Cache::remember("country_code_list", config('settings.cache_data_limit')['seconds'] * config('settings.cache_data_limit')['days'], function (){
                return Country::orderBy('display_name', 'ASC')
                    ->get(['id','full_name','dialling_code', 'country_code_alpha','flag','postcode_regexp']);
            });
            
            return response()->json(['status' => true, 'data' => $countryList], $this->successStatus);
        }
        catch (Exception $ex) {
            Auditable::log_audit_data('AddressApiController@country_list Exception',null,config('settings.log_type')[0],$ex->getMessage());
            return response()->json(['status' => false,'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
}
