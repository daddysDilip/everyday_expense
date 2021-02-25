<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        // pr($result);
        // pr($message);
        // die('bbbbbbbbbbb');
    	if(!empty($result))
    	{
    		$response = [
                'success' => true,
                'data'    => $result,
                'message' => $message,
                'code' => 200,
            ];

    	}else{
    		$response = [
                'success' => true,
                'message' => $message,
                'code' => 200,
            ];
    	}
        // pr($response); die;
        echo json_encode($response); die;
        // return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        // die('aaaaaaaaaaaaa');
    	$response = [
            'success' => false,
            'message' => $error,
            'code' => $code,
        ];
        if(!empty($errorMessages)){
            $last = (array)json_decode(json_encode($errorMessages));
            if(!empty($last))
            {
                foreach($last as $key => $val)
                {
                    $last['lst'.$key] = $val;
                    unset($last[$key]);
                }
            }
            $response['data'] = $last;
        }
        echo json_encode($response); die;
        // return response()->json($response, $code);
    }
}