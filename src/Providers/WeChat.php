<?php
namespace ZhzyTo\GeneralOauth2\Providers;

use ZhzyTo\GeneralOauth2\Appointments\ProviderInterface;
use ZhzyTo\GeneralOauth2\Exceptions\HttpException;
use ZhzyTo\GeneralOauth2\Exceptions\InvalidArgumentException;
use ZhzyTo\GeneralOauth2\Traits\HasHttpRequest;
use ZhzyTo\GeneralOauth2\User;

/**
 * 微信oauth2
 * Class WeChat
 * @package ZhzyTo\GeneralOauth2\Providers
 */
class WeChat extends BaseProvider implements ProviderInterface
{
    use HasHttpRequest;
    /**
     * @var
     */
    protected $appId;
    protected $appSecret;
    protected $redirectUri;
    protected $state = false;

    /**
     * WeChat constructor.
     * @param array $conf
     * @throws InvalidArgumentException
     */
    public function __construct(array $conf) {

        parent::__construct($conf);

        if (!$this->config_class->has("appId")) {
            throw new InvalidArgumentException("Missing config appId");
        }

        if (!$this->config_class->has("appSecret")) {
            throw new InvalidArgumentException("Missing config appSecret");
        }

        if (!$this->config_class->has("redirectUri")) {
            throw new InvalidArgumentException("Missing config redirectUri");
        }

        if ($this->config_class->has("state")) {
            $this->state = $this->config_class->get("state");
        }

        // 设定
        $this->appId = $this->config_class->get("appId");
        $this->appSecret = $this->config_class->get("appSecret");
        $this->redirectUri = $this->config_class->get("redirectUri");
    }

    /**
     * 获取授权认证地址
     * @param string|null $redirectUri
     * @return string
     */
    public function redirect(?string $redirectUri = null):string {
        // 设置地址
        if (!empty($redirectUri)) {
            $this->redirectUri = $redirectUri;
        }

        return $this->getAuthUri();
    }

    /**
     * 返回授权地址
     * @return string
     */
    protected function getAuthUri() :string {
        $param = [
            "appid" => $this->appId,
            "redirect_uri" => $this->redirectUri,
            "response_type" => "code",
            "scope" => "snsapi_userinfo", // snsapi_userinfo  snsapi_base
            "state" => $this->state ?? md5(uniqid('', true)),
        ];
        $query = $this->buildQueryParam($param);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?" . $query . '#wechat_redirect';
    }

    /**
     * 构造授权地址
     * @param array $param
     * @return string
     */
    protected function buildQueryParam(array $param): string {
        return http_build_query($param,'', '&',PHP_QUERY_RFC1738);
    }

    /**
     * 拉取用户信息
     * @param string $code
     * @return User
     * @throws HttpException
     * @throws \JsonException
     */
    public function pullUser(string $code): User {
        $token = $this->getToken($code);

        $param = [
            'access_token' => $token['access_token'],
            'openid' => $token['openid'],
            'lang' => 'zh_CN',
        ];

        $uri = "https://api.weixin.qq.com/sns/userinfo?" . $this->buildQueryParam($param);
        $response = $this->get($uri);

        if (!isset($response['openid'])) {
            throw new HttpException('Authorize Failed: ' . json_encode($response, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
        }

        return $this->responseToObject($response);
    }

    /**
     * @param string $code
     * @return array
     * @throws HttpException
     * @throws \JsonException
     */
    protected function getToken(string $code): array {
        $param = [
            "appid" => $this->appId,
            "secret" => $this->appSecret,
            "code" => $code,
            "grant_type" => "authorization_code",
        ];

        $query = $this->buildQueryParam($param);
        $uri = "https://api.weixin.qq.com/sns/oauth2/access_token?" . $query;

        $response = $this->get($uri);

        if (!isset($response['access_token'],$response['openid'])) {
            throw new HttpException('Authorize Failed: ' . json_encode($response, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
        }

        return $response;
    }

    /**
     * 将响应转为用户对象
     * @param array $user
     * @return User
     */
    protected function responseToObject(array $user): User
    {
        // 定义不同的Attribute
        return new User([
            'id' => $user['openid'] ?? null,
        ]);
    }
}

