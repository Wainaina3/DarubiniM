<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Property;
use App\Http\Requests;
use App\User;
use DB;
use Session;
use Validator;
use App\Http\Controllers\pesapal\checkStatus;
// require_once('pesapal\OAuth.php');
// require_once('checkStatus.php');
// require_once('db\dbconnector.php');

class AddPropertyController extends Controller
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
    * Method to add a property
    *
    * @return \Illuminate\Http\Response
    */
    function addOneProperty(){

      $counties= DB::table('counties')
      ->get();
      return view('add-property.addproperty',compact('counties'));
    }

    public function propertyLocation(Request $request)
    {
      if(\Request::ajax()) 
      {
        $county_id = $request::input(['county']); 
        echo "hi ".$county_id;
      }
      return \Response::json(); 
    }

    function addDataToDatabase(){

      $data_saved=true;
      if($data_saved){

        $this -> proceedToPayment();
      }
      else{

      }
    }
    function proceedToPayment(Request $request)
    {
      $amount=0;
      $desc = "listing of property ";
      $property_plan=  $request->input('property_plan');
      if (strcmp($property_plan,"Featured")==0) 
      {
        $desc = "Featured plan for listing property";
        $amount=500.00;
      }
      else
      {
        $desc = "Standard plan for listing property";
        $amount=300.00;
      }

    $amount = number_format($amount, 2);//format amount to 2 decimal places
    $type = "MERCHANT"; //default value = MERCHANT
    $reference = "";//unique order id of the transaction, generated by merchant
    $first_name = \Auth::user()->firstname; //[optional]
    $last_name = \Auth::user()->lastname; //[optional]
    $email = \Auth::user()->email;
    $phonenumber = \Auth::user()->phone;
    //ONE of email or phonenumber is required
    $send_user_data = 'http://localhost/pesa/pesapal/iframe.php?amount='.$amount.'&description='.$desc.'&type='.$type.'&reference='.$reference.'&first_name='.$first_name.'&last_name='.$last_name.'&email='.$email.'&phone_number='.$phonenumber.'&api=1&currency=KES'; 

    //send user data to the transaction sub domail url 
    return redirect()->away($send_user_data);
    }


    /**
    * Call back method to receive data from spayment subdomain 
    *
    * @return \Illuminate\Http\Response
    */
    function receivePesapalData()
    {
      if (\Auth::check())
      {
        $pesapal_merchant_reference = $_GET['ref'];
        $pesapal_transaction_tracking_id = $_GET['track'];
        $status  = $_GET['status'];
        $user_name=\Auth::user()->firstname;
        $user_id=\Auth::user()->id;
        $last_inserted_property= DB::table('properties')
        ->where('user_id',$user_id) 
        ->orderBy('property_id', 'DESC') 
        ->limit(1)
        ->pluck('property_id');
        $lastId = $last_inserted_property[0];

        if(strcmp($status,'COMPLETED')==0)
        {

          $updatedStatus= DB::table('properties')
          ->where('property_id',$lastId) 
          ->update(['paid_status'=>'2']);

          if($updatedStatus)
          {

            Session::flash('pesapal_success_flash_message','Thank you '.$user_name.' for listing your property on destate.co.ke. Your transaction was succesful.');
          }
        }
        else if(strcmp($status,'PENDING')==0)
        {
          $updatedStatus= DB::table('properties')
          ->where('property_id',$lastId) 
          ->update(['paid_status'=>'1']);

          if($updatedStatus)
          {
            Session::flash('pesapal_pending_flash_message','Thank you '.$user_name.' for listing your property on destate.co.ke. Your transaction is pending.');
          }
        }
        else if(strcmp($status,'INVALID')==0)
        {
          Session::flash('pesapal_failed_flash_message','Sorry, your transaction was cancelled .'); 
        }
      }

      return view('payment.payment');

    }


    /**
   * Call back method from pesapal
   *
   * @return \Illuminate\Http\Response
   */
    function pesapalCallback()
    {     
    }

    /**
     * save a property
     *
     * @return \Illuminate\Http\Response
     */
    public function storeProperty(Request $request)
    {
     // $validator=Validator::make($request->all(), [
      // 'county' => 'required|max:30',
      // 'sub_county' => 'required|max:20',
      // 'longitude' => 'numeric',
      // 'latitude' => 'numeric',
      // 'category' => 'required|max:20',
      // 'sale_rent' => 'required|max:5',
      // 'description' => 'required|max:100',
      // 'price' => 'required|numeric',
      // 'house_type' => 'required_if:category,House|min:4|max:20',
      // 'bedroom' => 'required_if:category,House',
      // 'bathroom' => 'required_if:category,House',
      // 'acres'=> 'required_if:category,Land|min:1|numeric',
    //   'files'    => 'sometimes|image|mimes:jpg,jpeg,JPEG,png,gif|max:6',
    //   ]);
    //  if ($validator->fails()) 
    //  {
    //   return redirect('/add-property')
    //   ->withErrors($validator)
    //   ->withInput();
    // }

      $pictureArray = array();
      if ($request->hasFile('files'))
      {
        $files = $request->file('files');
         // Making counting of uploaded images
        $file_count = count($files);
        if($file_count>6)
        {
          Session::flash('failed','Sorry, you can only upload a maximum of 6 pictures for one property');     
        }
        else{
    // start count how many uploaded
          $uploadcount = 0;
          foreach($files as $file)
          {
          // $filename = $file->getClientOriginalName();
            $fileSize= $file->getClientSize();
            if ($fileSize>2097152 ) 
            {
              Session::flash('failed','Sorry, Your picture is large. You can only upload pictures with a maximum size of 2MB');
            }
            else
            {
              $allowed =  array('gif','png' ,'jpg','jpeg', 'GIF','PNG','JPG', 'JPEG');
              $extension = $file->getClientOriginalExtension();
              if (!in_array($extension,$allowed) ) 
              {
               Session::flash('failed','Sorry, The type of file you uploaded is not accepted. Please uplod jpg, png or jpeg file');
             }
             else
             {
        $randNo=uniqid();//Generate a time based unique id
        $picture = date('md');//currrent month and day
        $picture=$picture."_".$randNo.".".$extension;//new name for the picture

        array_push($pictureArray, $picture);
        $destinationPath = base_path(). '/public/uploads';
        $file->move($destinationPath, $picture);
        $uploadcount ++; 

        if($uploadcount == $file_count)
        {
          if ($uploadcount==1) 
          {
           Session::flash('success',''.$uploadcount.' picture has been uploaded succesfully.');     
         }
         else{
          Session::flash('success',''.$uploadcount.' pictures have been uploaded succesfully.');
        } 
      }
    }    
  }
    // else
    // {
    //   Session::flash('failed','Sorry, pictures were not uploaded successfully'); 
    // }


}


    // if (\Auth::check())
    // {
    //   $input=  \Request::all();
    //   $insert=Alert::create($input);
    //   $user_name=\Auth::user()->firstname;

    //   Session::flash('flash_message','Thank you '.$user_name.' for adding a property to our site.');
    // }
return redirect()->back();
    // return view('add-property.addproperty',compact('pictureArray'));
}
}
}


    /**
     * Show all my properties.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMyProperties()
    {
     if (\Auth::check()){

       $user_id=\Auth::user()->id;

       $properties=DB::table('properties')
       ->leftJoin('house', 'properties.property_id', '=', 'house.property_id')
       ->leftJoin('land', 'properties.property_id', '=', 'land.property_id')
       ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
       ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
       ->where('user_id', $user_id)
       ->groupBy('properties.property_id')
       ->orderBy('created_at','desc')
       ->paginate(20);
     }
     return view('property.my-properties.my-property',compact('properties'));
// $user=\App\property::get();
//         return $user;
   }

   
     /**
     * Show edit a property
     *
     * @return \Illuminate\Http\Response
     */
     public function editProperty($id)
     {
       if (\Auth::check())
       {

         $user_id=\Auth::user()->id;

         $counties= DB::table('counties')
         ->get();
         //decrypt the url id parameter
         $id = decrypt($id);

         $property = DB::table('properties')        
         ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
         ->where('property_id', $id)
         ->where('user_id', $user_id)
         ->where('property_status','Enabled')
         ->get();
         if (count($property)!=1) 
         {
          abort(404);
        }
        // get house details
        $house= DB::table('house')
        ->where('property_id', $id)
        ->get();
        // get land details
        $land= DB::table('land')
        ->where('property_id', $id)
        ->get();
        //get property pictures
        $pictures= DB::table('pictures')
        ->where('property_id', $id)
        ->get();
      }
      return view('property.edit-property.edit-property', compact('property', 'house','land', 'pictures','counties'));
    }

     /**
     * save edited property details
     *
     * @return \Illuminate\Http\Response
     */
     public function saveEditedProperty(Request $request)
     {
      $user_id=\Auth::user()->id;
      if (\Auth::check())
      {

       $counties= DB::table('counties')
       ->get();

       $validator=Validator::make($request->all(), [
        'county' => 'required|max:30',
        'sub_county' => 'required|max:20',
        'longitude' => 'numeric',
        'latitude' => 'numeric',
        'category' => 'required|max:20',
        'sale_rent' => 'required|max:5',
        'description' => 'required|max:100',
        'price' => 'required|numeric',
        'house_type' => 'required_if:category,House|min:4|max:20',
        'bedroom' => 'required_if:category,House',
        'bathroom' => 'required_if:category,House',
        'acres'=> 'required_if:category,Land|min:1|numeric'
        ]);
       $property_id=  $request->input('property_id');
       if ($validator->fails()) 
       {
        return redirect('/property/edit-property/'.$property_id)
        ->withErrors($validator)
        ->withInput();
      }
      $property_id=  $request->input('property_id');
      $county=  $request->input('county');
      $sub_county=  $request->input('sub_county');
      $longitude=  $request->input('longitude');
      $latitude=  $request->input('latitude');
      $category=  $request->input('category');
      $sale_rent=  $request->input('sale_rent');
      $description=  $request->input('description');
      $price=  $request->input('price');
         //decrypt the property id 
      $property_id = decrypt($property_id);
      DB::table('properties')
      ->where('property_id',$property_id)
      ->where('user_id',$user_id)  
      ->update(['longitude'=>$longitude,'latitude'=>$latitude,'sale_rent'=>$sale_rent
        ,'county'=>$county,'sub_county'=>$sub_county,'price'=>$price
        ,'description'=>$description, 'category'=>$category]);

      if ($category =='House') {
        $house_type=  $request->input('house_type');
        $bedroom=  $request->input('bedroom');
        $bathroom=  $request->input('bathroom');
        $gym=  $request->input('gym');
        $water_storage=  $request->input('water_storage');
        $swimming_pool=  $request->input('swimming_poolwim');
        $kitchen_garden=  $request->input('kitchen_garden');
        $internet_accesss=  $request->input('internet_accesss');
        $disability_access=  $request->input('disability_access');
        $hr_security=  $request->input('hr_security');
        $cctv=  $request->input('cctv');
        $alarm_system=  $request->input('alarm_system');
        $electric_fence=  $request->input('electric_fence');
        $wall=  $request->input('wall');
        $watchdog=  $request->input('watchdog');
        $fully_furnished=  $request->input('fully_furnished');
        DB::table('house')
        ->where('property_id',$property_id) 
        ->update(['house_type'=>$house_type,'bathroom'=>$bathroom,'bedroom'=>$bedroom
          ,'gym'=>$gym,'water_storage'=>$water_storage,'swimming_pool'=>$swimming_pool
          ,'kitchen_garden'=>$kitchen_garden, 'internet_access'=>$internet_accesss,'disability_access'=>$disability_access
          ,'hr_security'=>$hr_security,'cctv'=>$cctv, 'alarm_system'=>$alarm_system
          , 'electric_fence'=>$electric_fence,'wall'=>$wall,'watchdog'=>$watchdog
          ,'fully_furnished'=>$fully_furnished]);
      }
      elseif ($category=='Land') {
       $acres=  $request->input('acres');
       DB::table('land')
       ->where('property_id',$property_id) 
       ->update(['acres'=>$acres]);
     }

     $user_name=\Auth::user()->firstname;

     Session::flash('flash_message','Thank you '.$user_name.' , Your property changes have been saved succesfully.');
   }
   $properties=DB::table('properties')
   ->leftJoin('house', 'properties.property_id', '=', 'house.property_id')
   ->leftJoin('land', 'properties.property_id', '=', 'land.property_id')
   ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
   ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
   ->where('user_id', $user_id)
   ->groupBy('properties.property_id')
   ->orderBy('created_at','desc')
   ->paginate(20);

   return view('property.my-properties.my-property',compact('properties'));

        // return redirect()->back();
        // return view('property.my-properties.my-property');
        // , compact('property', 'house','land', 'pictures','counties'));
 }

    /**
     * delete a property
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteProperty($id)
    {
     if (\Auth::check())
     {

       //decrypt the url id parameter
      $id = decrypt($id);

      $user_id=\Auth::user()->id;

      $property = Property:: where('property_id',$id);
      $property->delete();
      //   // delete pproperty
      // $property = DB::table('properties')
      // ->where('property_id', $id)
      // ->where('user_id', $user_id)
      // // ->get();
      //   ->delete();

      if ($property) 
      {
       Session::flash('success',' Your property has been deleted succesfully.');     
     }   
     else
     {
      Session::flash('failed',' Sorry, your property a not deleted. Please try again');
    }

  }
  return redirect()->back();
}
 /**
     * delete a picture
     *
     * @return \Illuminate\Http\Response
     */
 public function deletePicture($id)
 {
   if (\Auth::check())
   {
        //decrypt the picture id
    $id = decrypt($id);

    $picture = Property:: where('picture_id',$id);
    $picture->delete();

    if ($picture) 
    {
     Session::flash('delete_pic_success','Picture has been deleted succesfully.');     
   }   
   else
   {
    Session::flash('delete_pic_failed',' Sorry, picture was not deleted. Please try again');
  }

}
return redirect()->back();
}

}
