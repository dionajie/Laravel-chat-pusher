<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SocialAccountService;
use Socialite;
use Session;

class SocialAuthController extends Controller
{
    public function redirect($provider)
	{
	    return Socialite::driver($provider)->redirect();
	}
	
    public function callback(SocialAccountService $service, $provider)
	{
	    // Important change from previous post is that I'm now passing
	    // whole driver, not only the user. So no more ->user() part
	    $user = $service->createOrGetUser(Socialite::driver($provider));

	    auth()->login($user);

	  

	    return redirect()->to('/');
	}
}
