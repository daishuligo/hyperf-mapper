<?php

namespace Spider\Traits;

use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Spider\SpiderRequest;
use Spider\SpiderResponse;

trait ControllerTrait
{
    #[Inject]
    protected SpiderRequest $request;

    #[Inject]
    protected SpiderResponse $response;

    public function success(string|array|object $msgOrData = '', array|object $data = [], int $code = 200): ResponseInterface
    {
        if (is_string($msgOrData) || is_null($msgOrData)) {
            return $this->response->success($msgOrData, $data, $code);
        } else if (is_array($msgOrData) || is_object($msgOrData)) {
            return $this->response->success(null, $msgOrData, $code);
        } else {
            return $this->response->success(null, $data, $code);
        }
    }

    public function error(string $message = '', int $code = 500, array $data = []): ResponseInterface
    {
        return $this->response->error($message, $code, $data);
    }
}