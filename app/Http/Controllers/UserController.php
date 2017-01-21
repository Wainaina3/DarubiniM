<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Validator;
use App\User;
use DB;
use Hash;
use Mail;

class UserController extends Controller
{

   /**
 * Create a new controller instance.
 *
 * @return void
 */
   public function __construct()
   {	
   	$this->middleware('auth');
   }

 /**
 * Method to user profile page
 *
 * @return \Illuminate\Http\Response
 */
 function userProfile(){
 	$counties= \DB::table('counties')
 	->get();
 	$user_id=\Auth::user()->id;
 	$countynames= \DB::table('users')
 	->leftJoin('counties', 'users.county', '=', 'counties.county_id')
 	->where('id', $user_id)
 	->get();
 	$totalads=\DB::table('properties')
 	->where('user_id',$user_id)
 	->count();
 	$totalalerts=\DB::table('alerts')
 	->where('user_id',$user_id)
 	->count();

 	return view('profile.user-profile',compact('counties','countynames','totalads','totalalerts'));

 }

/**
 * Method to save edited details of a user profile
 *
 * @return \Illuminate\Http\Response
 */
function updateUserProfile(Request $request){
	if (\Auth::check())
	{
		$validator= Validator::make($request->all(), [
			'account_type' => 'required|max:20',
			'firstname' => 'required_if:account_type,Private Account|min:2|max:15',
			'lastname' => 'required_if:account_type,Private Account|min:2|max:15',
			'business_name' => 'required_if:account_type,Bussiness Account|min:2|max:30',
			'website' => 'required_if:account_type,Bussiness Account|min:2|max:40',
			'email' => 'required|email|max:50',
			'phone' => 'required|min:11|numeric',
			'county' => 'required|max:25',
			'sub_county' => 'required|max:25',
			]);
		if ($validator->fails()) 
		{
			return redirect('profile')
			->withErrors($validator)
			->withInput();
		}
		$user_id=\Auth::user()->id;

		$business_name=  $request->input('business_name');
		$website=  $request->input('website');
		$firstname=  $request->input('firstname');
		$lastname=  $request->input('lastname');
		$county=  $request->input('county');
		$sub_county=  $request->input('sub_county');
		$email=  $request->input('email');
		$phone=  $request->input('phone');

		if ($email==\Auth::user()->email && $phone!=\Auth::user()->phone) {
			$validator= Validator::make($request->all(), [
				'phone' => 'unique:users',
				]);
			if ($validator->fails()) 
			{
				return redirect('profile')
				->withErrors($validator)
				->withInput();
			}
			$update_account= DB::table('users')
			->where('id',$user_id) 
			->update(['firstname'=>$firstname,'lastname'=>$lastname
				,'county'=>$county,'sub_county'=>$sub_county
				,'phone'=>$phone]);
			if ($update_account) 
			{
				Session::flash('password_success_message','Your account details have been updated successfully.');
			}
			else
			{
				Session::flash('password_fail_message','Sorry, your account details were not updated successfully.');
			}
		}
		else if ($phone==\Auth::user()->phone && $email!=\Auth::user()->email) {
			$validator= Validator::make($request->all(), [
				'email' => 'unique:users',
				]);
			if ($validator->fails()) 
			{
				return redirect('profile')
				->withErrors($validator)
				->withInput();
			}
			$update_account= DB::table('users')
			->where('id',$user_id) 
			->update(['firstname'=>$firstname,'lastname'=>$lastname
				, 'business_name'=>$business_name, 'website'=>$website,'county'=>$county,'sub_county'=>$sub_county,'email'=>$email
				]);	
			if ($update_account) 
			{
				Session::flash('password_success_message','Your account details have been updated successfully.');
			}
			else
			{
				Session::flash('password_fail_message','Sorry, your account details were not updated. Please try again.');
			}
		}
		else if ($email==\Auth::user()->email && $phone==\Auth::user()->phone) {
			
			$update_account= DB::table('users')
			->where('id',$user_id) 
			->update(['firstname'=>$firstname,'lastname'=>$lastname
				,'business_name'=>$business_name, 'website'=>$website,'county'=>$county,'sub_county'=>$sub_county]);	
			if ($update_account) 
			{
				Session::flash('password_success_message','Your account details have been updated successfully.');
			}
			else
			{
				Session::flash('password_fail_message','Sorry, your account details were not updated .Please try again.');
			}
		}
		else  if ($email!=\Auth::user()->email && $phone!=\Auth::user()->phone){
			$validator= Validator::make($request->all(), [
				'email' => 'unique:users',
				'phone' => 'unique:users',
				]);
			if ($validator->fails()) 
			{
				return redirect('profile')
				->withErrors($validator)
				->withInput();
			}
			$update_account= DB::table('users')
			->where('id',$user_id) 
			->update(['firstname'=>$firstname,'lastname'=>$lastname
				,'business_name'=>$business_name, 'website'=>$website,'county'=>$county,'sub_county'=>$sub_county,'email'=>$email
				,'phone'=>$phone]);
			if ($update_account) 
			{
				Session::flash('password_success_message','Your account details have been updated successfully.');
			}
			else
			{
				Session::flash('password_fail_message','Sorry, your account details were not updated. Please try again.');
			}
		}
	}

	return redirect()->back();
}


/**
 * Method to save edited details of a user profile
 *
 * @return \Illuminate\Http\Response
 */
function changeUserPassword(Request $request){
	if (\Auth::check())
	{
		$validator= Validator::make($request->all(), [
			'old_password' => 'required|min:6|max:15',
			'password' => 'required|min:6|max:15|confirmed',
			]);
		if ($validator->fails()) 
		{
			return redirect('profile')
			->withErrors($validator)
			->withInput();
		}
		$user_id=\Auth::user()->id;
		$password1=  Hash::make($request->input('password'));
		$password2=  $request->input('password_confirmation');

		if (Hash::check($request->input('old_password'),\Auth::user()->password)) 
		{
			$updateuser=DB::table('users')

			->where('id',$user_id) 
			->update(['password'=>$password1]);
			if($updateuser)
			{
				$name=  \Auth::user()->firstname;

				Mail::send('email.successful_password_reset',  ['name' => $name], function ($message)  
				{
					$message->from('darubinirealestate@gmail.com', 'Darubini Real Estate');
					$message ->replyTo('darubinirealestate@gmail.com');

					$message->to(\Auth::user()->email, 'Darubini Real Estate')
					->subject('Darubini Real Esate Password Change');
				});
				Session::flash('password_success_message','Your password reset was successful.');
			}
			else
			{
				Session::flash('password_fail_message','Your password reset was not successful. Please try again.');
			}
		}
		else
		{
			Session::flash('password_fail_message','Wrong password, please provide correct current password.');
		}
	}
	return redirect()->back();
}
}
