<?php

namespace App\Traits;
use Log;
use App\Models\ClubMasterJourney;
use App\Models\Clubs;

trait Validation
{
    public $successStatus = 200;
    public function check_validation($fields, $error, $description){
        if ($error->fails()) {
            $errors = $error->errors();
            if(count($fields) > 0){
                foreach($fields as $keyField => $valueField){
                    if ($errors->first($keyField) != "") {
                        $apiResponse = ['status' => false, 'message' => $errors->first($keyField)];
                        return response()->json($apiResponse, $this->successStatus);
                    }
                }
            }
            else{
                return response()->json(['status' => true], $this->successStatus);
            }
        }
        else{
            return response()->json(['status' => true], $this->successStatus);
        }
    }
}
