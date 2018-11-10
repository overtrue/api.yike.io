<?php

namespace App\Http\Controllers;

use App\Profile;
use Socialite;

class OAuthController extends Controller
{
    /**
     * @param string $platform
     *
     * @return mixed
     */
    public function getRedirectUrl(string $platform)
    {
        return Socialite::driver($platform)->redirect()->getTargetUrl();
    }

    /**
     * @param string $platform
     *
     * @return array
     */
    public function handleCallback(string $platform)
    {
        try {
            $socialiteUser = Socialite::driver($platform)->stateless()->user();
        } catch (\Exception $e) {
            return abort(401, $e->getMessage());
        }

        return Profile::createFromSocialite($socialiteUser, $platform);
    }
}
