<?php

declare(strict_types=1);

namespace Spider;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Response;
use Psr\Http\Message\ResponseInterface;

class SpiderResponse extends Response
{
    public function success(string $message = null, array|object $data = [], int $code = 200): ResponseInterface
    {
        $format = [
            'success' => true,
            'message' => $message ?: 'success',
            'code'    => $code,
            'data'    => &$data,
        ];
        $format = $this->toJson($format);
        return $this->getResponse()
            ->withHeader('Server', 'MineAdmin')
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream($format));
    }

    public function error(string $message = '', int $code = 500, array $data = []): ResponseInterface
    {
        $format = [
            'success' => false,
            'code'    => $code,
            'message' => $message ?: 'fail',
        ];

        if (!empty($data)) {
            $format['data'] = &$data;
        }

        $format = $this->toJson($format);
        return $this->getResponse()
            ->withHeader('Server', 'MineAdmin')
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream($format));
    }
}