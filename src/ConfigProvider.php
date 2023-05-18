<?php

declare(strict_types=1);

namespace Spider;

use Hyperf\Database\Commands\CommandCollector;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for spider.',
                    'source' => __DIR__ . '/../publish/spider.php',
                    'destination' => BASE_PATH . '/config/autoload/spider.php',
                ],
            ],
        ];
    }
}
