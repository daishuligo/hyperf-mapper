<?php

namespace Spider\Util;

use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Container\ContainerInterface;
use Spider\SpiderRequest;

class SpiderUtil
{
    public static function container(): ContainerInterface
    {
        return ApplicationContext::getContainer();
    }

    public static function getLanguage(): ?string
    {
        $acceptLanguage = self::container()->get(SpiderRequest::class)->getHeaderLine('accept-language');
        return !empty($acceptLanguage) ? explode(',', $acceptLanguage)[0] : 'zh_CN';
    }

    public static function trans(string $key, $replace = []): string
    {
        return trans($key, $replace, self::getLanguage());
    }

    public static function console()
    {
        return self::container()->get(StdoutLoggerInterface::class);
    }
}