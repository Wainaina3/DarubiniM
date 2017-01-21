<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Reported;
use App\Property;
use DB;
use Session;
use Validator;

class ReportedController extends Controller
{
    /**
         * save /subscribe alerts.
         *
         * @return \Illuminate\Http\Response
         */
    public function reportProperty(Request $request)
    {
    	$validator=Validator::make($request->all(), [
    		'reason_for_report' => 'required|max:30',
    		'phone' => 'required|numeric|min:13',
    		'email' => 'required|email|max:50',
    		'message' => 'required|max:250',
    		]);
    	$property_id=  $request->input('property_id');
    	if ($validator->fails()) 
    	{
    		return redirect('/property/property-details/'.$property_id)
    		->withErrors($validator)
    		->withInput();
    	}
    	$input=  \Request::all();
    	$insert=Reported::create($input);

    	if($insert)
    	{
    		Session::flash('flash_message_report_post','Thank you for for your feedback.');
    	}
    	return redirect('/property/property-details/'.$property_id);
    }


    //testing method
    public function test(){

    	$reported=\App\Reported::find(1)->properties;

    	return $reported;
    }

}
