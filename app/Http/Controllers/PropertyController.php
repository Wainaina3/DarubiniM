<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Property;
use App\User;
use DB;
use Session;
use Validator;
// use App\Auth;
class PropertyController extends Controller
{
   /**
     * Create a new controller instance.
     *
     * 
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show all properties.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllProperties()
    {
       $counties= DB::table('counties')
       ->get();

        // $properties=Property::all();
       $properties=DB::table('properties')

       ->leftJoin('house', 'properties.property_id', '=', 'house.property_id')
       ->leftJoin('land', 'properties.property_id', '=', 'land.property_id')
       ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
       ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
       ->where('property_status','Enabled')
       ->groupBy('properties.property_id')
       ->orderBy('property_plan','desc')
       ->paginate(2);

       // return $properties;

       return view('property.all-properties.allproperties',compact('properties','counties'));
   }

     /**
     * Show house properties
     *
     * @return \Illuminate\Http\Response
     */
     public function houses()
     {
       $counties= DB::table('counties')
       ->get();

       $cat='House';
       $properties=DB::table('properties')
       ->leftJoin('house', 'properties.property_id', '=', 'house.property_id')
       ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
       ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
       ->where('category', $cat)
       ->where('property_status','Enabled')
       ->groupBy('properties.property_id')
       ->orderBy('property_plan','desc')
       ->paginate(2);

       return view('property.houses.house',compact('properties','counties'));
   }


     /**
     * search house properties
     *
     * @return \Illuminate\Http\Response
     */
     public function searchHouses(Request $request)
     {        
         $validator=Validator::make($request->all(), [
            'sale_rent' => 'max:4',
            'house_type' => 'max:20',
            'county' => 'max:30',
            'sub_county' => 'max:20',
            'price_from' => 'numeric',
            'price_to' => 'numeric',
            ]);
         if ($validator->fails()) 
         {
            return redirect('property/houses')
            ->withErrors($validator)
            ->withInput();
        }
        $counties= DB::table('counties')
        ->get();

        $cat='House';
        $sale_rent=  $request->input('sale_rent');
        $house_type=  $request->input('house_type');
        $county=  $request->input('county');
        $sub_county=  $request->input('sub_county');
        $price_from=  $request->input('price_from');
        $price_to=  $request->input('price_to');
        $bedroom=  $request->input('bedroom');
        $bathroom=  $request->input('bathroom');

        //Dynamic search query
        $my_query =DB::table('properties')
        ->leftJoin('house', 'properties.property_id', '=', 'house.property_id')
        ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
        ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
        ->where('property_status','Enabled')
        ->where('category', $cat);
        if($sale_rent!=""){
            $my_query=$my_query ->where('sale_rent',$sale_rent);
        }
        if($house_type!="")
        {
            $my_query=$my_query ->where('house_type',$house_type);
        }
        if($county!="")
        {
            $my_query=$my_query ->where('county',$county);
        }
        if($sub_county!="")
        {
            $my_query=$my_query ->where('sub_county',$sub_county);
        }
        if($bedroom!="")
        {
            $my_query=$my_query ->where('bedroom','=',$bedroom);
        }
        if($bathroom!="")
        {
            $my_query=$my_query ->where('bathroom','>=',$bathroom);
        }
        if($price_from!="")
        {
            $my_query=$my_query ->where('price','>=',$price_from);
        }
        if($price_to!="")
        {
            $my_query=$my_query ->where('price','<=',$price_to);
        }

        $my_query=$my_query ->groupBy('properties.property_id')
        ->orderBy('property_plan','desc');
        $properties= $my_query ->paginate(20);

        return view('property.houses.house',compact('properties','counties'));
    }
    /**
     * search lands properties
     *
     * @return \Illuminate\Http\Response
     */
    public function searchlands(Request $request)
    {        
     $validator=Validator::make($request->all(), [
        'sale_rent' => 'max:4',
        'county' => 'max:30',
        'sub_county' => 'max:20',
        'acres' => 'max:20',
        'price_from' => 'numeric',
        'price_to' => 'numeric',
        ]);
     if ($validator->fails()) 
     {
        return redirect('property/lands')
        ->withErrors($validator)
        ->withInput();
    }
    $counties= DB::table('counties')
    ->get();

    $cat='Land';
    $sale_rent=  $request->input('sale_rent');
    $county=  $request->input('county');
    $sub_county=  $request->input('sub_county');
    $price_from=  $request->input('price_from');
    $price_to=  $request->input('price_to');
    $acres=  $request->input('acres');
         if (@$acres!="") {  //if the input is not null proceed               
        $myacre=explode(',', $acres); //split the acre input into an array
        $minimum_acre=$myacre[0];//lowest land size
        $maximum_acre=$myacre[1]; //highest land size 
    }
        //Dynamic search query
    $my_query =DB::table('properties')
    ->leftJoin('land', 'properties.property_id', '=', 'land.property_id')
    ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
    ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
    ->where('category', $cat)
    ->where('property_status','Enabled');
    if($sale_rent!=""){
        $my_query=$my_query ->where('sale_rent',$sale_rent);
    }
    if($county!="")
    {
        $my_query=$my_query ->where('county',$county);
    }
    if($sub_county!="")
    {
        $my_query=$my_query ->where('sub_county',$sub_county);
    }
    if($acres!="")
    {
        $my_query=$my_query ->where('acres','>=',$minimum_acre);
    }
    if($acres!="")
    {
        $my_query=$my_query ->where('acres','<=',$maximum_acre);
    }
    if($price_from!="")
    {
        $my_query=$my_query ->where('price','>=',$price_from);
    }
    if($price_to!="")
    {
        $my_query=$my_query ->where('price','<=',$price_to);
    }

    $my_query=$my_query ->groupBy('properties.property_id')
    ->orderBy('property_plan','desc');
    $properties= $my_query ->paginate(20);

       // return $properties;

    return view('property.lands.land',compact('properties','counties'));
}
    /**
     * search all properties- used by homepage and all properties page
     *
     * @return \Illuminate\Http\Response
     */
    public function searchAllProperties(Request $request)
    {        
     $validator=Validator::make($request->all(), [
        'sale_rent' => 'max:4',
        'category' => 'max:5',
        'house_type' => 'max:20',
        'county' => 'max:30',
        'sub_county' => 'max:20',
        'price_from' => 'numeric',
        'price_to' => 'numeric',
        'acres' => 'max:20',
        ]);
     if ($validator->fails()) 
     {
        return redirect('property/all-properties')
        ->withErrors($validator)
        ->withInput();
    }
    $counties= DB::table('counties')
    ->get();

    $cat=$request->input('category');
    $sale_rent=  $request->input('sale_rent');
    $house_type=  $request->input('house_type');
    $county=  $request->input('county');
    $sub_county=  $request->input('sub_county');
    $price_from=  $request->input('price_from');
    $price_to=  $request->input('price_to');
    $bedroom=  $request->input('bedroom');
    $bathroom=  $request->input('bathroom');
    $acres=  $request->input('acres');
        if (@$acres!="") {  //if the input is not null proceed               
        $myacre=explode(',', $acres); //split the acre input into an array
        $minimum_acre=$myacre[0];//lowest land size
        $maximum_acre=$myacre[1]; //highest land size 
    }

        //Dynamic search query for all properties
    $my_query =DB::table('properties')
    ->leftJoin('house', 'properties.property_id', '=', 'house.property_id')
    ->leftJoin('land', 'properties.property_id', '=', 'land.property_id')
    ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
    ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
    ->where('property_status','Enabled');
    if($cat!=""){
        $my_query=$my_query ->where('category', $cat);
    }
    if($sale_rent!=""){
        $my_query=$my_query ->where('sale_rent',$sale_rent);
    }
    if($house_type!="")
    {
        $my_query=$my_query ->where('house_type',$house_type);
    }
    if($county!="")
    {
        $my_query=$my_query ->where('county',$county);
    }
    if($sub_county!="")
    {
        $my_query=$my_query ->where('sub_county',$sub_county);
    }
    if($bedroom!="")
    {
        $my_query=$my_query ->where('bedroom','=',$bedroom);
    }
    if($bathroom!="")
    {
        $my_query=$my_query ->where('bathroom','>=',$bathroom);
    }
    if($acres!="")
    {
        $my_query=$my_query ->where('acres','>=',$minimum_acre);
    }
    if($acres!="")
    {
        $my_query=$my_query ->where('acres','<=',$maximum_acre);
    }
    if($price_from!="")
    {
        $my_query=$my_query ->where('price','>=',$price_from);
    }
    if($price_to!="")
    {
        $my_query=$my_query ->where('price','<=',$price_to);
    }

    $my_query=$my_query ->groupBy('properties.property_id')
    ->orderBy('property_plan','desc');
    $properties= $my_query ->paginate(20);


    return view('property.all-properties.allproperties',compact('properties','counties'));
}


     /**
     * Show land properties
     *
     * @return \Illuminate\Http\Response
     */
     public function lands()
     {
       $counties= DB::table('counties')
       ->get();
       $cat='Land';
       $properties=DB::table('properties')
       ->leftJoin('land', 'properties.property_id', '=', 'land.property_id')
       ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
       ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
       ->where('category', $cat)
       ->where('property_status','Enabled')
       ->where('properties.deleted_at','NULL')
       ->groupBy('properties.property_id')
       ->orderBy('property_plan','desc')
       ->paginate(2);

       return view('property.lands.land',compact('properties','counties'));
   }


     /**
     * Show property details.
     *
     * @return \Illuminate\Http\Response
     */
     public function propertyDetails($id)
     {
         //decrypt the url id parameter
        $id = decrypt($id);
       
        // $property=Property::find($id);
        $property = DB::table('properties')
        ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
        ->where('property_id', $id)
        ->where('property_status','Enabled')
        ->get();

        if (count($property)!=1) {
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
        //convert result to collection and get the user id
        $collection = collect($property);
        $user_id=$collection->pluck('user_id');

        // Get the user details with a given id
        $user= DB::table('users')
        ->select('id', 'firstname','lastname','phone','email')
        ->where('id', $user_id)
        ->get();

        // return compact('property','house','pictures', 'user');
        return   view('property.property-details.property-details',compact('property','house','land','pictures', 'user'));
    }

     /**
     * Show homapage properties at a random
     *
     * @return \Illuminate\Http\Response
     */
     public function homePage()
     {
       $counties= DB::table('counties')
       ->get();
       $properties=DB::table('properties')

       ->leftJoin('house', 'properties.property_id', '=', 'house.property_id')
       ->leftJoin('land', 'properties.property_id', '=', 'land.property_id')
       ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
       ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
       ->where('property_plan','Featured')
       ->where('property_status','Enabled')
       ->groupBy('properties.property_id')
       ->orderBy('created_at','desc')
       ->get();
     //  $collection = collect($prop);
       //$properties = $collection->random(2);//randomly select 20 properties
       // return  $properties;
       return view('welcome',compact('properties','counties'));
   }


    /**
     * quick search for all properties page
     *
     * @return \Illuminate\Http\Response
     */
    public function allQuickSearch(Request $request)
    {        
     $validator=Validator::make($request->all(), [
        'quick_search' => 'max:30',
        ]);
     if ($validator->fails()) 
     {
        return redirect('property/all-properties')
        ->withErrors($validator)
        ->withInput();
    }
    $counties= DB::table('counties')
    ->get();

    $search_text=$request->input('quick_search');

        //Dynamic search query for all properties
    $my_query =DB::table('properties')
    ->leftJoin('house', 'properties.property_id', '=', 'house.property_id')
    ->leftJoin('land', 'properties.property_id', '=', 'land.property_id')
    ->leftJoin('counties', 'properties.county', '=', 'counties.county_id')
    ->leftJoin('pictures', 'properties.property_id', '=', 'pictures.property_id')
    ->where('property_status','Enabled');
    if($search_text!=""){
        $my_query=$my_query ->where('category','like', $search_text);
    }
    if($search_text!=""){
        $my_query=$my_query ->where('sale_rent','like', $search_text);
    }
    if($search_text!="")
    {
        $my_query=$my_query ->where('house_type', 'like', $search_text);
    }
    if($search_text!="")
    {
        $my_query=$my_query ->where('county', 'like', $search_text);
    }
    if($search_text!="")
    {
        $my_query=$my_query ->where('sub_county', 'like', $search_text);
    }
    if($search_text!="")
    {
        $my_query=$my_query ->where('bedroom', 'like', $search_text);
    }
    if($search_text!="")
    {
        $my_query=$my_query ->where('bathroom', 'like', $search_text);
    }
    if($search_text!="")
    {
        $my_query=$my_query ->where('acres', '<=', $search_text);
    }
    if($search_text!="")
    {
        $my_query=$my_query ->where('price', '<=', $search_text);
    }

    $my_query=$my_query ->groupBy('properties.property_id')
    ->orderBy('property_plan','desc');
    $properties= $my_query ->paginate(20);


    return view('property.all-properties.allproperties',compact('properties','counties'));
}
     /**
     * Show the last property id from the database
     *
     * @return \Illuminate\Http\Response
     */
     public function getLastPropertyId()
     {

        $property_id=DB::table('properties')

        ->max('properties.property_id');

        return $property_id;
    }
    
}
