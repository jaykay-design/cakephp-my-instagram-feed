<?php
namespace Instagram\Lib;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Http\Client;
use DateInterval;
use DateTime;

class OAuth
{

    public static function exchangeCodeForToken($code)
    {
        $client = new Client();
        $response = $client->post(
            'https://api.instagram.com/oauth/access_token',
            [
                'client_id' => Configure::read('Instagram.App.client_id'),
                'client_secret' => Configure::read('Instagram.App.client_secret'),
                'grant_type' => 'authorization_code',
                'redirect_uri' => 'https://www.thewindgames.com/OAuth/authorize',
                'code' => $code,
            ]
        );

        return $response->getJson();
    }

    public static function exchangeForLonglivedToken($access_token)
    {
        $client = new Client();
        $response = $client->get("https://graph.instagram.com/access_token", [
            'grant_type' => 'ig_exchange_token',
            'client_secret' => Configure::read('Instagram.App.client_secret'),
            'access_token' => $access_token,
        ]);

        return $response->getJson();
    }

    public static function refreshLonglivedToken($access_token)
    {
        $client = new Client();
        $response = $client->get("https://graph.instagram.com/refresh_access_token", [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $access_token,
        ]);

        return $response->getJson();
    }

    public static function getOAuthData()
    {
        $oauth = Cache::read('Instagram.OAuth', 'persistent');

        if ($oauth['expires'] < (new DateTime())->sub(new DateInterval("PT1M"))) {
            $token = OAuth::refreshLonglivedToken($oauth['token']);
            OAuth::cacheToken($token['access_token'], $token['expires_in']);

            return Cache::read('Instagram.OAuth', 'persistent');
        }

        return $oauth;
    }

    public static function cacheToken($token, $expires)
    {
        Cache::write('Instagram.OAuth', [
            'token' => $token,
            'expires' => (new DateTime())->add(new DateInterval('PT' . $expires . 'S')),
        ], 'persistent');

    }

}
