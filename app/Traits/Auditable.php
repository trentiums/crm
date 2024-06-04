<?php

namespace App\Traits;

use App\Models\AuditLog;
use GuzzleHttp\Psr7\HttpFactory;
use Http\Factory\Guzzle\ResponseFactory;
use Http\Factory\Guzzle\ServerRequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use Http\Factory\Guzzle\UploadedFileFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::audit('audit:created', $model);
        });

        static::updated(function (Model $model) {
            self::audit('audit:updated', $model, $model->getChanges());
        });

        static::deleted(function (Model $model) {
            self::audit('audit:deleted', $model);
        });
    }

    protected static function audit($description, $model, $changes = [])
    {
        AuditLog::create([
            'description'  => $description,
            'subject_id'   => $model->id ?? null,
            'subject_type' => sprintf('%s#%s', get_class($model), $model->id) ?? null,
            'user_id'      => auth()->id() ?? null,
            'properties'   => $changes ?: $model,
            'host'         => request()->ip() ?? null,
        ]);
    }

    public static function log_audit_data($description, $model = null, $log_type = null, $api_response = NULL){
        $dataInsert['description'] = $description;
        $dataInsert['user_id'] = auth()->id() ?? null;
        $dataInsert['host'] = request()->ip() ?? null;
        $dataInsert['log_type'] = $log_type ?? config('settings.log_type')[2];
        $dataInsert['api_request'] = json_encode(request()->all());

        $psrServerRequest = self::convert_request_response_to_psr7(request(), true);
        $arrServerRequest = [];
        if(!empty($psrServerRequest)){
            $arrServerRequest['parsedBody'] = self::get_reflection_props($psrServerRequest, 'parsedBody');
            $arrServerRequest['cookieParams'] = self::get_reflection_props($psrServerRequest, 'cookieParams');
            $arrServerRequest['serverParams'] = self::get_reflection_props($psrServerRequest, 'serverParams');
            $arrServerRequest['uploadedFiles'] = self::get_reflection_props($psrServerRequest, 'uploadedFiles');
        }
        $serverRequest = json_encode($arrServerRequest);
        $dataInsert['server_request'] = $serverRequest;
        if(!empty($api_response)){
            $dataInsert['api_response'] = json_encode($api_response);
        }
        if(!empty($model)){
            $dataInsert['subject_id'] = $model->id ?? null;
            $dataInsert['subject_type'] = get_class($model) ?? null;
            $dataInsert['properties'] = $model ?? null;
        }
        AuditLog::create($dataInsert);
    }

    public static function convert_request_response_to_psr7($objData = null, $is_request = true){
        if($objData != null) {
            // PSR7 factory for request
            $psrFactory = new PsrHttpFactory(
                new ServerRequestFactory(),
                new StreamFactory(),
                new UploadedFileFactory(),
                new ResponseFactory()
            );

            // Setup a request/response model
            if($is_request) return $psrFactory->createRequest($objData);
            if(!$is_request) return $psrFactory->createResponse($objData);
        }
        return '';
    }

    public static function get_reflection_props($server_request, $prop_type){
        $myClassReflection = new \ReflectionClass(get_class($server_request));
        $secret = $myClassReflection->getProperty($prop_type);
        $secret->setAccessible(true);
        return $secret->getValue($server_request);
    }
}
