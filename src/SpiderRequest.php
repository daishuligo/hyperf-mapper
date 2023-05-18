<?php

declare(strict_types=1);

namespace Spider;

use Hyperf\Collection\Arr;
use Hyperf\HttpServer\Request;

class SpiderRequest extends Request
{
    public function only(array $keys): array
    {
        return Arr::only($this->all(), $keys);
    }

    public function ip(): string
    {
        $ip = $this->getServerParams()['remote_addr'] ?? '0.0.0.0';
        $headers = $this->getHeaders();

        if (isset($headers['x-real-ip'])) {
            $ip = $headers['x-real-ip'][0];
        } else if (isset($headers['x-forwarded-for'])) {
            $ip = $headers['x-forwarded-for'][0];
        } else if (isset($headers['http_x_forwarded_for'])) {
            $ip = $headers['http_x_forwarded_for'][0];
        }

        return $ip;
    }

    public function setProperty(string $name, $value)
    {
        parent:$this->storeRequestProperty($name, $value);
    }

    public function getProperty(string $name)
    {
        return parent::getRequestProperty($name);
    }
}