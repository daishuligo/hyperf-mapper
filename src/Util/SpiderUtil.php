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

    public static function trans(string $key, $replace = []): string
    {
        $acceptLanguage = self::container()->get(SpiderRequest::class)->getHeaderLine('accept-language');
        $language = !empty($acceptLanguage) ? explode(',', $acceptLanguage)[0] : 'zh_CN';
        return trans($key, $replace, $language);
    }

    public static function console()
    {
        return self::container()->get(StdoutLoggerInterface::class);
    }
}