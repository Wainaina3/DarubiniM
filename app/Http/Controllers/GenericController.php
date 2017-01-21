<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Session;
use Validator;
use App\Http\Requests;
use DB;
use Mail;

class GenericController extends Controller
{
   
  /**
   * Create a new controller instance.
   *
   * @return void
   */
    // public function __construct()
    // {
    // 	$this->middleware('auth');
    // }

     /**
     * Method to contact page
     *
     * @return \Illuminate\Http\Response
     */
     function contact()
     {

     	return view('contact.contact');

     }

    /**
     * send email on contact form.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendContactEmail(Request $request)
    {
     $validator=Validator::make($request->all(), [
      'youremail' => 'required|email|max:50',
      'subject' => 'required|max:20',
      'user_name' => 'required|max:20',
      'phone' => 'required|min:11|numeric',
      'content' => 'required|max:300',
      ]);
     if ($validator->fails()) 
     {
      return redirect('contact/')
      ->withErrors($validator)
      ->withInput();
    }
    // $youremail=  $request->input('youremail');
    // $subject=  $request->input('subject');
    $content=  Input::get('content');
    $name=  Input::get('user_name');
    $sender_email=  Input::get('youremail');
    $phone=  Input::get('phone');

    Mail::send('email.contact_form',  ['content' => $content,'name' => $name,'sender_email' => $sender_email,'phone' => $phone], function ($message)  
    {
      $message->from(Input::get('youremail'), Input::get('user_name'));
      $message ->replyTo(Input::get('youremail'));

      $message->to('wbenmurimi@gmail.com', 'Darubini Real Estate')
      ->subject(Input::get('user_name').':  '. Input::get('subject'));
    });

    Session::flash('flash_message','Thank you for reaching to us. Your message was sent successfully.');

    return redirect()->back();
  }

   /**
     * send email to share a property.
     *
     * @return \Illuminate\Http\Response
     */
   public function shareViaEmail(Request $request)
   {
     $validator=Validator::make($request->all(), [
      'youremail' => 'required|email|max:50',
      'receiveremail' => 'required|email|max:50',
      'user_name' => 'required|max:20',
      'content' => 'required|max:200',
      ]);
     $property_id=  $request->input('property_id');
     if ($validator->fails()) 
     {
      return redirect('/property/property-details/'.$property_id)
      ->withErrors($validator)
      ->withInput();
    }

    $content=  Input::get('content');
    $name=  Input::get('user_name');
    $sender_email=  Input::get('youremail');
    $receiveremail=  Input::get('receiveremail');

    Mail::send('email.email_share_form',  ['content' => $content,'name' => $name,'sender_email' => $sender_email,'receiveremail' => $receiveremail], function ($message)  
    {
      $message->from(Input::get('youremail'), Input::get('user_name'));
      $message ->replyTo(Input::get('youremail'));

      $message->to(Input::get('receiveremail'), 'Darubini Real Estate')
      ->subject('Darubini Real Estate');
    });

    Session::flash('flash_message_share_email','Thank you for sharing this property. Your message was sent successfully.');

    return redirect('/property/property-details/'.$property_id);
  }


  /**
     * send email to seller.
     *
     * @return \Illuminate\Http\Response
     */
   public function contactSeller(Request $request)
   {
     $validator=Validator::make($request->all(), [
      'youremail' => 'required|email|max:50',
      'seller_email' => 'required|email|max:50',
      'yourname' => 'required|max:20',
      'yourphone' => 'required|min:11|numeric',
      'content' => 'required|max:200',
      ]);
     $property_id=  $request->input('property_id');
     if ($validator->fails()) 
     {
      return redirect('/property/property-details/'.$property_id)
      ->withErrors($validator)
      ->withInput();
    }

    $content=  Input::get('content');
    $name=  Input::get('yourname');
    $sender_email=  Input::get('youremail');
    $seller_email=  Input::get('seller_email');
    $phone=  Input::get('yourphone');

    Mail::send('email.contact_seller_form',  ['content' => $content,'name' => $name,'sender_email' => $sender_email,'phone' => $phone,'seller_email' => $seller_email], function ($message)  
    {
      $message->from(Input::get('youremail'), Input::get('yourname'));
      $message ->replyTo(Input::get('youremail'));

      $message->to(Input::get('seller_email'), 'Darubini Real Estate')
      ->subject('Darubini Real Estate');
    });

    Session::flash('flash_message_contact_seller','Thank you for choosing Darubini Real Estate. Your message was sent successfully.');

    return redirect('/property/property-details/'.$property_id);
  }

   /**
   * Method to about page
   *
   * @return \Illuminate\Http\Response
   */
   function about()
   {
   	return view('about.about-us');

   }
   /**
   * Method to about page
   *
   * @return \Illuminate\Http\Response
   */
   function passwordReset()
   {
    return view('auth.passwords.reset2');

  }
   /**
   * Method to terms page
   *
   * @return \Illuminate\Http\Response
   */
   function termsAndCondition()
   {
   	return view('terms-and-conditions.terms');
   }
    /**
   * Method to privacy policy page
   *
   * @return \Illuminate\Http\Response
   */
    function privacyPolicy()
    {
      return view('privacy.privacy');
    }
    /**
   * Method to safe buying and selling page
   *
   * @return \Illuminate\Http\Response
   */
    function safeBuying()
    {
      return view('selling-buying-tips.selling-tips');
    }
       /**
       * General packages information.
       *
       * @return \Illuminate\Http\Response
       */
       public function packageInfo()
       {

          // return $packages;
         return view('package.package-info.package-info');
       }

   /**
       * Load Register counties.
       *
       * @return \Illuminate\Http\Response
       */
   public function getRegisterCounties()
   {
    $counties= DB::table('counties')
    ->get();
    return view('auth.register', compact('counties'));
  }

}