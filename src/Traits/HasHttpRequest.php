<?php
namespace ZhzyTo\GeneralOauth2\Traits;

use ZhzyTo\GeneralOauth2\Exceptions\HttpException;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;


/**
 * Trait HasHttpRequest
 * @package app\traits
 */
trait HasHttpRequest
{
    /**
     * send get
     * @param $endpoint
     * @param array $query
     * @param array $headers
     * @return mixed
     * @throws HttpException
     */
    protected function get($endpoint, $query = [], $headers = [])
    {
        return $this->request('get', $endpoint, [
            'headers' => $headers,
            'query' => $query,
        ]);
    }

    /**
     * send post
     * @param $endpoint
     * @param array $params
     * @param array $headers
     * @return mixed
     * @throws HttpException
     */
    protected function post($endpoint, $params = [], $headers = [])
    {
        return $this->request('post', $endpoint, [
            'headers' => $headers,
            'form_params' => $params,
        ]);
    }

    /**
     * action send
     * @param $method
     * @param $endpoint
     * @param array $options
     * @return mixed
     * @throws HttpException
     */
    protected function request($method, $endpoint, $options = [])
    {
        try {
            return $this->unwrapResponse($this->getHttpClient($this->getBaseOptions())->{$method}($endpoint, $options));
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array $options
     * @return Client
     */
    protected function getHttpClient(array $options = []): Client
    {
        return new Client($options);
    }

    /**
     * Return base Guzzle options.
     *
     * @return array
     */
    protected function getBaseOptions(): array
    {
        $options = method_exists($this, 'getGuzzleOptions') ? $this->getGuzzleOptions() : [];

        return \array_merge($options, [
            'base_uri' => method_exists($this, 'getBaseUri') ? $this->getBaseUri() : '',
            'timeout' => method_exists($this, 'getTimeout') ? $this->getTimeout() : 5.0,
        ]);
    }

    /**
     * @param ResponseInterface $response
     * @return mixed|string
     * @throws \JsonException
     */
    protected function unwrapResponse(ResponseInterface $response): string
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $contents = $response->getBody()->getContents();

        if (false !== stripos($contentType, 'json') || stripos($contentType, 'javascript')) {
            return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        }

        if (false !== stripos($contentType, 'xml')) {
            return json_decode(json_encode(simplexml_load_string($contents), JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        }

        return $contents;
    }
}