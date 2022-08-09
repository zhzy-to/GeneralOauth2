<?php
namespace ZhzyTo\GeneralOauth2\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ZhzyTo\GeneralOauth2\Providers\WeChat;

class WechatTest extends TestCase
{
    /**
     *  ./vendor/bin/phpunit --filter testRedirect
     * @throws \ZhzyTo\GeneralOauth2\Exceptions\InvalidArgumentException
     */
    public function testRedirect(): void
    {
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf0e81c3bee622d60&redirect_uri=http%3A%2F%2Fnba.bluewebgame.com%2Foauth_response.php&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";

        $app = new WeChat([
            'appId' => "wxf0e81c3bee622d60",
            'appSecret' => "wxf0e81c3bee622d60",
            'redirectUri' => "http://nba.bluewebgame.com/oauth_response.php",
            'state' => "STATE",
        ]);
        $redirect = $app->redirect();

        $this->assertSame($url, $redirect);
    }
}
