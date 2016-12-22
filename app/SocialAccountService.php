<?php

namespace App;

use Laravel\Socialite\Contracts\Provider;
use Session;

class SocialAccountService
{
    public function createOrGetUser(Provider $provider)
    {

        $providerUser = $provider->user();


        $providerName = class_basename($provider);

        $account = SocialAccount::whereProvider($providerName)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        // dd($account);
        if ($account) {
            return $account->user;
        } else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $providerName
            ]);

            $user = User::whereEmail($providerUser->getId())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'avatar' => str_replace('http://','https://',$providerUser->getAvatar()),
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;

        }

    }
}