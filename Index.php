<?php

require __DIR__ . '/vendor/autoload.php';

use ZhzyTo\GeneralOauth2\Application;
use ZhzyTo\GeneralOauth2\Providers\WeChat;

$conf = [
    "appId" => "wxa8a0b1b3305ac636",
    "appSecret" => "63e1f590bcae616b366dbc825bb29b29",
    "redirectUri" => "12321",
];
//$wechat = Application::Wechat($conf);
//$wechat2 = Application::create("Wechat",$conf);
//$wechat = new WeChat($conf);

//var_dump($wechat->redirect());

//var_dump($wechat->pullUser('12312312'));
