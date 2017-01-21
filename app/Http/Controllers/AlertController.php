<?php

namespace App\Http\Controllers;

// use Request;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Alert;
use App\User;
use DB;

class AlertController extends Controller
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
         * Show subscribe alerts.
         *
         * @return \Illuminate\Http\Response
         */
        public function subscribeAlert()
        {
           $counties= DB::table('counties')
           ->get();
                    // return $counties;
           return view('subscribe.subscribe-alert.subscribe-alert', compact('counties'));
       }


         /**
         * Show my alerts.
         *
         * @return \Illuminate\Http\Response
         */
         public function myAlert()
         {
           if (\Auth::check()){

             $user_id=\Auth::user()->id;
             $alerts_count= DB::table('alerts')
             ->leftJoin('counties', 'alerts.county', '=', 'counties.county_id')
             ->where('user_id', $user_id)
             ->count();

             $alerts= \App\User::find(\Auth::user()->id)->alerts()
                   ->orderBy('created_at','desc')
                   ->get();
         }
            // return $alerts;
         return view('subscribe.my-alerts.savedAlerts',compact('alerts','alerts_count'));
     }

          /**
         * Show alert details.
         *
         * @return \Illuminate\Http\Response
         */
          public function alertDetails($id)
          {
             if (\Auth::check())
             {
                //decrypt the url alert id parameter
               $id = decrypt($id);

                $user_id=\Auth::user()->id;
                $userAlert= DB::table('alerts')
                ->leftJoin('counties', 'alerts.county', '=', 'counties.county_id')
                ->where('id', $id)
                ->where('user_id', $user_id)
                ->get();
                if (count($userAlert)!=1) 
                {
                    abort(404);
                }
            }
                // return $alert;
            return view('subscribe.alert-details.alert-detail',compact('userAlert'));
        }
        /**
        * Edit alert details.
        *
        * @return \Illuminate\Http\Response
        */
        public function editAlert($id)
        {
            if (\Auth::check())
            {
                //decrypt the url alert id parameter
              $id = decrypt($id);

                $counties= DB::table('counties')
                ->get();
                $user_id=\Auth::user()->id;
                $userAlert= DB::table('alerts')
                ->leftJoin('counties', 'alerts.county', '=', 'counties.county_id')
                ->where('id', $id)
                ->where('user_id', $user_id)
                ->get();
                if (count($userAlert)!=1) 
                {
                    abort(404);
                }
            }
            return view('subscribe.edit-alert.edit-alert',compact('userAlert','counties'));
        }

        /**
         * Show my packages.
         *
         * @return \Illuminate\Http\Response
         */
        public function myPackages()
        {
           if (\Auth::check())
           {

             $user_id=\Auth::user()->id;
             $packages_count=  DB::table('packages')
             ->where('user_id', $user_id)
             ->count();

             $packages= DB::table('packages')
             ->where('user_id', $user_id)
             ->get();
         }
            // return $packages;
         return view('package.my-packages.packages',compact('packages','packages_count'));
     }

    /**
         * Show package details.
         *
         * @return \Illuminate\Http\Response
         */
    public function packageDetails($id)
    {
        if (\Auth::check())
        {
            $max_package= DB::table('packages')
            -> max('package_id');
            if ($id >$max_package) 
            {
                abort(404);
            }

            $user_id=\Auth::user()->id;
            $packages= DB::table('packages')
            ->where('package_id', $id)
            ->where('user_id', $user_id)
            ->get();
            if (count($packages)!=1) 
            {
                abort(404);
            }
        }
                // return $alert;
        return view('package.package-details.package-details',compact('packages'));
    }

        /**
         * buy packages.
         *
         * @return \Illuminate\Http\Response
         */
        public function buyPackages()
        {

            // return $packages;
         return view('package.buy-package.buy-package');
     }

         /**
         * save /subscribe alerts.
         *
         * @return \Illuminate\Http\Response
         */
         public function storeAlert(Request $request)
         {
             $validator=Validator::make($request->all(), [
                'alert_via' => 'required|max:7',
                'sale_rent' => 'required|max:4',
                'county' => 'required|max:30',
                'sub_county' => 'required|max:20',
                'price_from' => 'required|numeric',
                'price_to' => 'required|numeric',
                'category' => 'required|max:20',
                'acres'=> 'required_if:category,Land|min:1|numeric',
                'house_type'=>'required_if:category,House|min:4|max:20'
                ]);
             if ($validator->fails()) 
             {
                return redirect('subscribe/subscribe-alert')
                ->withErrors($validator)
                ->withInput();
            }

            if (\Auth::check())
            {
            $input=  \Request::all();
              // $insert=Alert::create($input);             
            $addedAlert= \Auth::user()->alerts()->save( new Alert($input));

             $user_name=\Auth::user()->firstname;
             if($addedAlert)
             {
              Session::flash('flash_message','Thank you '.$user_name.' for subscribing to our alerts.');
          }
          else
            {
                Session::flash('flash_message_add_fail','Sorry, your alert has not been saved succesfully. Please try again'); 
            }
          }
          return redirect()->back();
      }

      /**
         * save edited alert details.
         *
         * @return \Illuminate\Http\Response
         */
         public function saveEditedAlertDetails(Request $request)
         {
              if (\Auth::check())
            {
             $validator=Validator::make($request->all(), [
                'id' => 'required|max:11',
                'alert_via' => 'required|max:7',
                'sale_rent' => 'required|max:4',
                'county' => 'required|max:30',
                'sub_county' => 'required|max:20',
                'price_from' => 'required|numeric',
                'price_to' => 'required|numeric',
                'category' => 'required|max:20',
                'acres'=> 'required_if:category,Land|min:0.25|numeric',
                'house_type'=>'required_if:category,House|min:4|max:20'
                ]);
              $alert_id=  $request->input('id');
             if ($validator->fails()) 
             {
                return redirect('subscribe/edit-alert/'.$alert_id)
                ->withErrors($validator)
                ->withInput();
            }
            
             $alert_via=  $request->input('alert_via');
             $sale_rent=  $request->input('sale_rent');
             $county=  $request->input('county');
             $sub_county=  $request->input('sub_county');
             $price_from=  $request->input('price_from');
             $price_to=  $request->input('price_to');
             $category=  $request->input('category');
             $acres=  $request->input('acres');
             $house_type=  $request->input('house_type');

              //decrypt the url alert id
              $decrypted_alert_id = decrypt($alert_id);

              $updatedAlert= DB::table('alerts')
              ->where('id',$decrypted_alert_id) 
              ->update(['alert_via'=>$alert_via,'sale_rent'=>$sale_rent
                ,'county'=>$county,'sub_county'=>$sub_county,'price_from'=>$price_from
                ,'price_to'=>$price_to, 'category'=>$category,'acres'=>$acres,
                'house_type'=>$house_type]);

              $user_name=\Auth::user()->firstname;
              if($updatedAlert)
              {
              Session::flash('flash_message_edit','Thank you '.$user_name.' , Your alert changes have been saved succesfully.');
          }
           else
            {
                Session::flash('flash_message_edit_fail','Your alert changes have not been saved succesfully. Please try again'); 
            }
          }
         return redirect('subscribe/alert-details/'.$alert_id);
      }

      /**
         * Enable a disabed alert .
         *
         * @return \Illuminate\Http\Response
         */
         public function enableAlert(Request $request)
         {
             $alert_id=  $request->input('id');
             $alert_status=  $request->input('alert_status');             
             
            if (\Auth::check())
            {
            //decrypt the url alert id
            $decrypted_alert_id = decrypt($alert_id);

              $input=  \Request::all();
             $alertEnable= DB::table('alerts')
              ->where('id',$decrypted_alert_id) 
              ->update(['alert_status'=>$alert_status]);

              $user_name=\Auth::user()->firstname;
              if($alertEnable)
              {
              Session::flash('flash_message_alert','Thank you '.$user_name.' , Your alert has been enabled succesfully. Continue enjoying free alerts');
            }
            else
            {
                Session::flash('flash_message_alert_fail','Sorry, Your alert has not been enabled succesfully. Please try again'); 
            }
          }
          return redirect('subscribe/alert-details/'.$alert_id);
      }
      /**
         * Enable a disabed alert .
         *
         * @return \Illuminate\Http\Response
         */
         public function disableAlert(Request $request)
         {
             $alert_id=  $request->input('id');
             $alert_status=  $request->input('alert_status');
             
            if (\Auth::check())
            {
            //decrypt the url alert id
            $decrypted_alert_id = decrypt($alert_id);

             $alertEnable= DB::table('alerts')
              ->where('id',$decrypted_alert_id) 
              ->update(['alert_status'=>$alert_status]);

              $user_name=\Auth::user()->firstname;

              if($alertEnable)
              {
              Session::flash('flash_message_alert','Thank you '.$user_name.' , Your alert has been disabled succesfully. You will not receive more alerts');
            }
            else
            {
                Session::flash('flash_message_alert_fail','Sorry, Your alert has not been disabled succesfully. Please try again'); 
            }
          }
          return redirect('subscribe/alert-details/'.$alert_id);

      }

  }
