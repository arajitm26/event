<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use \Firebase\JWT\JWT;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use URL;
// instantiate and use the dompdf class


class TestController extends Controller
{
    //

    public function login(Request $request)
    {
    	
      $validator = Validator::make($request->all(), [
            'phone_no'      => 'required|min:10',
            //'password'      => 'required|min:4',
        ]);

        if($validator->fails())
        {
	      return response()->json(["status"=>false,"data"=>(object)[],"message"=>$validator->messages()->all()[0]], 200);
	    }
	    else
	    {
	    	
        $user_data=User::where("phone_no",$request->phone_no)->first();
      //dd($user_data);

	        if ($user_data != null) 
	        {
             if(Auth::loginUsingId($user_data->id))
             {
              $user = User::where('phone_no', $request->phone_no)->first();

               
               unset($user["created_at"]);
               unset($user["updated_at"]);
              return response()->json(["status"=>true,"data"=>$user,"message"=>"successfully logged in"], 200);
             }
             else
            {

             return response()->json(["satus"=>false,"data"=>(object)[],"message"=>"Authenticate failed"], 404);
            }
	           
	           
	           
	        }
	        else
            {

           	 return response()->json(["satus"=>false,"data"=>(object)[],"message"=>"Mobile number not registered"], 404);
            }
	    	
	    }
    }

    public function register()
    {
    	return "register";
    }

    public function get_event_list()
    {
    	return ["event1","event2"];
    }

    public function dompdf()
    {
    	$dompdf = new Dompdf();
    	dd($dompdf);
    }

    public function set_jwt()
    {
		$key = "example_key";
		$payload = array(
		    "iss" => "http://example.org",
		    "aud" => "http://example.com",
		    "iat" => 1356999524,
		    "nbf" => 1357000000
		);

		/**
		 * IMPORTANT:
		 * You must specify supported algorithms for your application. See
		 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
		 * for a list of spec-compliant algorithms.
		 */
		$jwt = JWT::encode($payload, $key);

		echo $jwt;
    }


    public function get_jwt()
    {
    	$key = "example_key";


			/**
			 * IMPORTANT:
			 * You must specify supported algorithms for your application. See
			 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
			 * for a list of spec-compliant algorithms.
			 */
			$jwt="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDB9.KcNaeDRMPwkRHDHsuIh1L2B01VApiu3aTOkWplFjoYI";

			try {
			$decoded = JWT::decode($jwt, $key, array('HS256'));
			                
			                echo "<pre>";
			                var_dump($decoded);
			            } catch (Exception $e) {
			                //echo $e;

			                echo "verification failed";
			            }

    }
}
