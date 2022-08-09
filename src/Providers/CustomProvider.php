<?php
namespace ZhzyTo\GeneralOauth2\Providers;

use ZhzyTo\GeneralOauth2\Appointments\CustomProviderInterface;
use ZhzyTo\GeneralOauth2\Exceptions\HttpException;
use ZhzyTo\GeneralOauth2\Exceptions\InvalidArgumentException;
use ZhzyTo\GeneralOauth2\Traits\HasHttpRequest;

class CustomProvider extends BaseProvider implements CustomProviderInterface
{
    use HasHttpRequest;

    protected string $accessTokenUri;

    protected string $userinfoUri;

    public function __construct(array $conf) {
        parent::__construct($conf);

        $this->userinfoUri = $this->config_class->get("userinfoUri");
        $this->accessTokenUri = $this->config_class->get("accessTokenUri");
    }

    /**
     * @return mixed
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getAccessToken() {
        if (!$this->accessTokenUri) {
            throw new InvalidArgumentException("Missing accessTokenUri");
        }

        return $this->get($this->accessTokenUri);
    }

    /**
     * @return mixed
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getUserInfo() {
        if (!$this->userinfoUri) {
            throw new InvalidArgumentException("Missing userinfoUri");
        }

        return $this->get($this->userinfoUri);
    }
}