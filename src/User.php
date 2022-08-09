<?php
namespace ZhzyTo\GeneralOauth2;

/**
 * Class User
 * @package ZhzyTo\GeneralOauth2
 */
class User
{
    public ?string $id;

    protected array $attributes = [];

    public function __construct(array $attributes) {
        $this->attributes = $attributes;
    }

    /**
     * 获取用户身份标识
     * @return string|null
     */
    public function getId(): ?string {
        return $this->getAttribute('id');
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function getAttribute(string $name, $default = null): ?string {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * 获取响应内容
     * @return array
     */
    public function getAttributes(): array {
        return $this->attributes;
    }
}
