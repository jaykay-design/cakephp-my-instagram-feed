<?php
namespace Instagram\View\Helper;

use Cake\Cache\Cache;
use Cake\Http\Client;
use Cake\View\Helper;
use Instagram\Lib\OAuth;

class InstagramHelper extends Helper
{

    private $__cacheConfig;

    public function initialize(array $config)
    {
        $config = array_merge(['cache_config' => 'default'], $config);

        $this->__cacheConfig = $config['cache_config'];
    }

    public function getItems()
    {
        $oauth = OAuth::getOAuthData();
        $token = $oauth['token'];

        $data = Cache::remember('Instagram', function () use ($token) {
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
            Cache::delete('Instagram', $this->__cacheConfig);

            return null;
        }

        return $data['data'];
    }
}
