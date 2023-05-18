<?php

namespace Spider\Commands;

use Hyperf\Command\Annotation\Command;

#[Command]
class RequestCommand extends GeneratorCommand
{
    public function __construct()
    {
        parent::__construct('make:request');
    }

    public function configure()
    {
        $this->setDescription('创建一个新的请求类');

        parent::configure();
    }

    protected function getStub(): string
    {
        return $this->getConfig()['stub'] ?? __DIR__ . '/stubs/request.stub';
    }

    protected function getDefaultNamespace(): string
    {
        return $this->getConfig()['namespace'] ?? 'App\\Request';
    }

    protected function getSuffix(): string
    {
        return $this->getConfig()['suffix'] ?? 'Request';
    }
}