<?php
namespace ZhzyTo\GeneralOauth2\Appointments;

use ZhzyTo\GeneralOauth2\User;

/**
 * 定于每个服务应该存在的方法
 * Interface ProviderInterface
 * @package ZhzyTo\GeneralOauth2\Appointments
 */
interface ProviderInterface
{
    /**
     * 构造授权链接
     * 并返回重定向地址
     * @param string|null $redirectUri
     * @return string
     */
    public function redirect(?string $redirectUri = null): string;

    /**
     * 拉取用户信息
     * @param string $code
     * @return User
     */
    public function pullUser(string $code): User;
}