<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    protected function sendErrorResponse( $statusCode, $errorCode, $meta = null ){

        $dataObject = (object)array();


        $errorObject = (object)array();
        $errorObject->status = $statusCode;
        $errorObject->code = $errorCode;
        $errorObject->title = __('error.error_code_'.$errorCode.'_title');
        $errorObject->detail = __('error.error_code_'.$errorCode.'_detail');

        $dataObject->errors = [$errorObject];

        if( isset( $meta ) ){
          $dataObject->meta = $meta;
        }

        return response()->json($dataObject, $statusCode );

    }

    protected function sendSuccessResponse( $statusCode, $data, $meta = null ){

        $dataObject = (object)array();

        $dataObject->data = $data;

        if( isset( $meta ) ){
          $dataObject->meta = $meta;
        }

        return response()->json($dataObject, $statusCode );

    }
}
