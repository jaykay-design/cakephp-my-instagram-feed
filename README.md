# Instagram feed plugin for CakePHP 3.x

Show your own Instagram feed in CakePHP

## Table of Contents
* [Installation](#installation)
* [Preparation](#preparation)
* [Configuration](#configuration)
* [Usage](#basic-usage)
* [Bugs and Feedback](#bugs-and-feedback)
* [License](#license)

## Installation
You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

Run the following command
```sh
composer require jaykay-design/cakephp-my-instagram-feed
 ```
You can then load the plugin using the shell command:
```sh
bin/cake plugin load -b MyInstagramFeed
```
Or you can manually add the loading statement in the **config/boostrap.php** file of your application:

```php
Plugin::load('MyInstagramFeed', ['routes' => true]);
```
or in CakePHP >= 3.8 add this statement in the **src/Application.php** file of your application

```php
public function bootstrap()
    {
        ...
        $this->addPlugin(\MyInstagramFeed\Plugin::class, ['routes' => true]);
        ...
```

Add the view helper in **src/View/AppView.php**

```php
public function initialize()
{

    ...
    $this->loadHelper('Instagram.Instagram');    
    ...
```


## Preparation
To be able to get your Instagram feed data you will have to provide a client ID and client secret. These can be obtained by creating a Facebook App for Instagram. Follow these [instructions](https://developers.facebook.com/docs/instagram-basic-display-api/getting-started).

When asked to provide an OAuth callback url enter this: [Your domain]/MyInstagramFeed/OAuth/authorize

## Configuration
Default configuration:
```php
'MyInstagramFeed' => [
    'client_id' => '',
    'client_secret' => '',
    'cache_config' => 'default'
],
```
This configuration is automatically merged with your application specific configuration preferentially using any keys you define.

* client_id (string) - The client id provided to you when you [set up](#setup) the app
* client_secret (string) - The client secret provided to you when you [set up](#setup) the app
* callback_url (string) - The callback url you provided when you [set up](#setup) the app. It should have this format [Your domain]/MyInstagramFeed/OAuth/authorize
* cache_config - The cache configuration for the instagram feed data


## Basic Usage

Typically you define these keys in your **config/app.php** file:
```php
'MyInstagramFeed' => [
    'client_id' => 'some large number',
    'client_secret' => 'a very large string',
    'cache_config' => 'default'
],
```

Once you have Installed and configured the plugin visit this page of your site: [your domain]/MyInstagramFeed/OAuth and you will be shown a link named "Authorize". After you click on it you will be asked if this app is allowed to access your Instagram feed. Agree to all options.


In your template where you want to show your Instagram feed add this code:

```php
<?php 
    $items = $this->Instagram->getItems();
    foreach ($items as $item) { ?>
    <a href="<?=$item['permalink']?>">
        <img 
            src="<?=empty($item['thumbnail_url']) ? $item['media_url'] : $item['thumbnail_url'] ?>" 
            alt="<?=$item['caption'] ?>" 
            title="<?=$item['caption'] ?>">
    </a>
<?php } ?>

```


## Bugs and Feedback
https://github.com/jaykay-design/cakephp-my-instagram-feed/issues


## License
Copyright (c) 2017 John Caprez

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
