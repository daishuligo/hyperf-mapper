<?php

declare(strict_types=1);

namespace Spider;

use Hyperf\Database\Commands\CommandCollector;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'commands' => [
                ...$this->getDatabaseCommands(),
            ],
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

    private function getDatabaseCommands(): array
    {
        if (! class_exists(CommandCollector::class)) {
            return [];
        }

        return CommandCollector::getAllCommands();
    }
}
