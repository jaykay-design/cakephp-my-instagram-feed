<?php
namespace MyInstagramFeed\View\Helper;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\View\Helper;
use MyInstagramFeed\Lib\OAuth;

class InstagramHelper extends Helper
{

    private $__cacheConfig;

    public function initialize(array $config)
    {
        $this->__cacheConfig = Configure::read('MyInstagramFeed.cache_config');
    }

    public function getItems()
    {
        $oauth = OAuth::getOAuthData();
        $token = $oauth['token'];

        $data = Cache::remember('MyInstagramFeed', function () use ($token) {
            $client = new Client();

            $request = $client->get('https://graph.instagram.com/me/media',
                [
                    'access_token' => $token,
                    'fields' => 'caption,id,media_url,thumbnail_url,permalink',
                ]);

            $data = $request->getJson();
            if (array_key_exists('data', $data)) {
                foreach ($data['data'] as &$d) {
                    if (!array_key_exists('caption', $d)) {
                        $d['caption'] = '';
                    }
                }
            }

            return $data;

        }, $this->__cacheConfig);

        if (array_key_exists('error', $data)) {
            Cache::delete('MyInstagramFeed', $this->__cacheConfig);

            return null;
        }

        return $data['data'];
    }
}
