<?php
        namespace App\Http\Controllers;
        use Illuminate\Http\Request;
        use App\Http\Controllers\Controller as Controller;
 
        class BaseController extends Controller
        {
            /**
            * success response method.
            *
            * @return \Illuminate\Http\Response
            */
 
            public function sendResponse($result, $message, $error_response = '')
            {
                if($error_response == '')
                {
                    $response = [
                        'success' => true,
                        'data'    => $result,
                        'message' => $message,
                    ];
                }
                else
                {
                    $response = [
                        'success' => true,
                        'data'    => $result,
                        'message' => $message,
                        'error_response' => $error_response
                    ];
                }
                return response()->json($response, 200);
            }

            public function sendImageResponse($result, $message, $error_response = '')
            {
                if($error_response == '')
                {
                    $response = [
                        'success' => true,
                        'data'    => $result,
                        'message' => $message,
                    ];
                }
                else
                {
                    $response = [
                        'success' => true,
                        'data'    => $result,
                        'message' => $message,
                        'error_response' => $error_response
                    ];
                }
                return response()->json($response, 200,['Content-type'=> 'application/json/jpg; charset=utf-8']);
            }
 
            /**
            * return error response.
            *
            * @return \Illuminate\Http\Response
            */
 
            public function sendError($error, $errorMessages = [], $code)
            {
                $response = [
                    'success' => false,
                    'message' => $error,
                ];
 
                if(!empty($errorMessages)){
                    $response['data'] = $errorMessages;
                }
                return response()->json($response, $code);
            }

                    // return response()->json([
                //     'message' => implode(" ", $validateRequest->errors()->all()),
                //     'errors' => $validateRequest->errors()->all(),
                // ], 422);
        }
    
