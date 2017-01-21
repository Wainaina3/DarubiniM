<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Socialite;

class SocialAuthController extends Controller
{
     public function redirect()
    {
        return Socialite::with('facebook')->redirect();   
    }   

    public function callback()
    {
        // when facebook call us a with token 
        $providerUser = \Socialite::with('facebook')->user();   
    }
}
