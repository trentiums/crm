<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;
use DB;
use Hash;
use App\Traits\Validation;

class LoginApiController extends Controller
{
    use Validation;

    public $successStatus = 200;

    /**
     * @api {post} /api/v1/login Login
     * @apiSampleRequest off
     * @apiName Login
     * @apiGroup Authentication
     * @apiVersion 1.0.0
     *
     * @apiDescription <span class="type type__post">Login API</span>
     *
     *   When user try to login with manually than `email` & `password` required
     *
     *   API request content-type [{"key":"Content-Type","value":"application/json"}]
     *
     * @apiParam {String}     email       Email
     *
     *   Validate `email` field is required.
     *
     *   Validate `email` Length Minimum 2 characters.
     *
     *   Validate `email` with `rfc,dns` (Laravel Default email validation).
     *
     *   Validate `email` is exists or not
     *
     * @apiParam {String}    password        Security Password for account login
     *
     *   Validate `password` field is required.
     *
     *   Validate `password` is string
     *
     *   Validate `password` field must be minimum 8 character.
     *
     *   Validate `password` field should have at least 1 lowercase AND 1 uppercase AND 1 number.
     *
     * @apiSuccess {Boolean}   status                               Response successful or not
     * @apiSuccess {Array}    data                              Login success data with token
     *
     * @apiParamExample {json} Request-Example:
     *      {
     *          "email" : "admin@mailinator.com",
     *          "password" : "password"
     *      }
     *
     * @apiExample {curl} Example usage:
     *       curl -i https://crm.trentiums.com/api/v1/login
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *      "status": true,
     *      "data": {
     *          "name": "Kishan123",
     *          "email": "kishan@trentiums.com",
     *          "id": 3,
     *          "token": "5|Wl6TZYNwVvuJZsnPgTRyDHJdij0PwocNTdGnhKdU34f261b9"
     *      }
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "Something wen't wrong please try again"
     *     }
     *
     *     HTTP/1.1 200 Bad Request
     *     {
     *       "status": false,
     *       "message": "There is some issue with your account detail, please try again."
     *     }
     */
    public function login(Request $request)
    {
        try {
            $userRequest = $request->all();
            $fields['password'] = [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ];
            $fields['email'] = [
                'required',
                'min:2',
                'email:rfc,dns',
                'exists:users,email',
            ];

            $error = Validator::make($request->all(), $fields, [
                'email.required' => trans('label.email_required_error_msg'),
                'email.string' => trans('label.email_string_error_msg'),
                'email.email' => trans('label.email_format_error_msg'),
                'email.exists' => trans('label.email_exists_error_msg'),
                'password.required' => trans('label.password_required_error_msg'),
                'password.string' => trans('label.password_string_error_msg'),
                'password.min' => trans('label.password_min_error_msg'),
                'password.regex' => trans('label.password_regex_error_msg'),
            ]);

            $validationResponse = $this->check_validation($fields, $error, 'Login');
            if (!$validationResponse->getData()->status) {
                return $validationResponse;
            }

            $user = User::where('email', '=', $userRequest['email'])
                ->where("user_role", "!=", array_flip(Role::ROLES)['Admin'])
                ->first(['name', 'email', 'user_role', 'id', 'password']);

            if ($user) {
                // email exist
                if (Hash::check(trim($userRequest['password']), $user->password)) {
                    $userParam = array(
                        'email' => $userRequest['email'],
                        'password' => trim($userRequest['password'])
                    );

                    if (Auth::attempt($userParam)) {
                        $tokenName = 'HSui78' . date("Y") . date("Y-m-d H:i:s A");
                        $user->token = $user->createToken($tokenName)->plainTextToken;
                        unset($user->password);
                        unset($user->created_at);
                        unset($user->email_verified_at);
                        unset($user->updated_at);
                        return response()->json(['status' => true, 'data' => $user], $this->successStatus);
                    } else {
                        Auditable::log_audit_data('LoginApiController@login unauthenticated', null, config('settings.log_type')[1], $userRequest);
                        return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
                    }
                } else {
                    Auditable::log_audit_data('LoginApiController@login invalid password', null, config('settings.log_type')[1], $userRequest);
                    return response()->json(['status' => false, 'message' => trans('label.password_string_error_msg')], $this->successStatus);
                }
            }
            else {
                Auditable::log_audit_data('LoginApiController@login email not exists', null, config('settings.log_type')[1], $userRequest);
                return response()->json(['status' => false, 'message' => trans('label.invalid_login_credential_error_msg')], $this->successStatus);
            }
        } catch (Exception $ex) {
            Auditable::log_audit_data('LoginApiController@login Exception', null, config('settings.log_type')[0], $ex->getMessage());
            return response()->json(['status' => false, 'message' => trans('label.something_went_wrong_error_msg')], $this->successStatus);
        }
    }
}
