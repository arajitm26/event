<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Otp;
use App\Token;
use App\Category;
use App\Event;
use App\BookedEvent;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use URL;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    public function login($phone_no)
    {
    	
      $validator = Validator::make(['phone_no'=>$phone_no], [
            'phone_no'      => 'required|min:10',
            //'password'      => 'required|min:4',
        ]);

        if($validator->fails())
        {
	      return response()->json(["status"=>false,"data"=>(object)[],"message"=>$validator->messages()->all()[0]], 200);
	    }
	    else
	    {
            $otp = new Otp;

	        $otp->phone_no = $phone_no;
	        $otp->otp = rand(1000,9999);
	        
	        if($otp->save())
	        {
	        	return response()->json(["status"=>true,"otp"=>$otp->otp], 200);
	        }
	        else
	        {
	        	return response()->json(["status"=>false,"message"=>"database error"], 502);
	        }
	    }
    }

    public function verify_otp(Request $request)
    {
         $data=Otp::where("phone_no",$request->phone)->where("otp",$request->otp)->first();
	     if($data != null)
	     {
	     	Otp::where('phone_no', $request->phone)->delete();
           $user = User::firstOrCreate([
			    'phone' => $request->phone
			]);
           
           
           if($user)
           {
           	$token = Token::updateOrCreate(
			    ['user_id' => $user->id],
			    ['token' => md5(time().rand())]
			);

			return response()->json(["status"=>true,"token"=>$token->token,"user_id"=>$user->id], 200);

		  }
           
	     }
	     else
	     {
	     	return response()->json(["status"=>false,"message"=>"Wrong otp"], 404);
	     }
    }

    public function update_location(Request $request)
    {
       //dd($request->all());	
       $user=User::find($request->user_id);
       $user->location=$request->location;
       if($user->save())
       {
			return response()->json(["status"=>true], 200);

       }

    }


    public function get_event_list()
    {
       $event=Event::select("id","title","image","place")->get();
       foreach ($event as $key => $value) 
       {
       	$event[$key]["image"]=URL::to('/public/').Storage::url("images/event/").$event[$key]["image"];
       }
       
       
       
       if ($event != null) 
       {
       	return response()->json(["status"=>true,"data"=>$event,"message"=>"Event list"], 200);
       }
       else
       {
       	return response()->json(["status"=>false,"data"=>[],"message"=>"No data found"], 404);
       }
       
       
    }


    public function get_trending_events_parties()
    {
      $event=Event::select("id","title","image","place")->where("is_trending",1)->get();
       foreach ($event as $key => $value) 
       {
       	$event[$key]["image"]=URL::to('/public/').Storage::url("images/event/").$event[$key]["image"];
       }
       
       
       
       if ($event != null) 
       {
       	return response()->json(["status"=>true,"data"=>$event,"message"=>"Trending events"], 200);
       }
       else
       {
       	return response()->json(["status"=>false,"data"=>[],"message"=>"No data found"], 404);
       }	
    }

    public function get_categories()
    {
    	$category=Category::select("id","name","image")->get();
       foreach ($category as $key => $value) 
       {
       	$category[$key]["image"]=URL::to('/public/').Storage::url("images/category/").$category[$key]["image"];
       }
       
       
       
       if ($category != null) 
       {
       	return response()->json(["status"=>true,"data"=>$category,"message"=>"Category list"], 200);
       }
       else
       {
       	return response()->json(["status"=>false,"data"=>[],"message"=>"No data found"], 404);
       }
    }


    public function get_my_booked_events(Request $request,$user_id)
    {
    	$time=time();
    	$events = DB::table('users')
    	    ->select('events.id as id', 'events.image as image','title','place','events.event_date as date','events.event_time as time','amount as amout per person','booked_evens.person as no-of-tickets','booked_evens.ticket as ticket-number')
            ->join('booked_evens', 'users.id', '=', 'booked_evens.user_id')
            ->join('events', 'events.id', '=', 'booked_evens.event_id')
            ->where('users.id',$user_id);
            if ($request->event_type == "upcoming") 
            {
            	$events->where('events.event_date', '>', date('d-m-Y'));
            }
            elseif($request->event_type == "trending") 
            {
            	$events->where('is_trending', 1);
            }
            
            $events=$events->get();
        if(count($events) != 0)
        {
        	foreach($events as $key => $value)
        	{
               $events[$key]->image=URL::to('/public/').Storage::url("images/event/").$events[$key]->image;
        	}

          return response()->json(["status"=>true,"data"=>$events,"message"=>"Booked event list"], 200);
        }
        else
        {
          return response()->json(["status"=>false,"data"=>[],"message"=>"No data found"], 404);
        }
    }

}
