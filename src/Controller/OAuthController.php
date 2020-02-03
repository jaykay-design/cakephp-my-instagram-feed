<?php

namespace Instagram\Controller;

use Cake\Core\Configure;
use Cake\Routing\Router;
use Instagram\Lib\OAuth;

class OAuthController extends \App\Controller\AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->disableAutoLayout();
    }

    public function index()
    {
        $this->set('client_id', Configure::read('Instagram.App.client_id'));
        $this->set('base_url', Router::fullBaseUrl());
    }

    public function authorize()
    {
        $code = $this->request->getQuery('code');

        $short_token = OAuth::exchangeCodeForToken($code);
        $long_token = OAuth::exchangeForLonglivedToken($short_token['access_token']);

        OAuth::cacheToken($long_token['access_token'], $long_token['expires_in']);
    }

}
