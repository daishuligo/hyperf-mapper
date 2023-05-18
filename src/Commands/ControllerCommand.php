<?php

namespace Spider\Commands;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Command\Annotation\Command;

#[Command]
class ControllerCommand extends GeneratorCommand
{
    public function __construct()
    {
        parent::__construct('make:controller');
    }

    public function configure()
    {
        $this->setDescription('创建一个新的控制器类');

        parent::configure();
    }

    protected function buildClass(string $name): string
    {
        $stub = file_get_contents($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceService($stub)
            ->replaceRequest($stub)->replaceClass($stub, $name);
    }

    protected function replaceService(string &$stub)
    {
        $service = $this->input->getOption('service') ?? $this->getNameInput();
        $serviceName = $service . 'Service';
        $uses = $this->getServiceNamespace() . '\\' . $serviceName;
        $stub = str_replace(
            ['%SERVICE%', '%SERVICE_USES%'],
            [$serviceName, $uses],
            $stub
        );

        return $this;
    }

    protected function replaceRequest(string &$stub)
    {
        $request = $this->input->getOption('request') ?? $this->getNameInput();
        $requestName = $request . 'Request';
        $uses = $this->getRequestNamespace() . '\\' . $requestName;
        $stub = str_replace(
            ['%REQUEST%', '%REQUEST_USES%'],
            [$requestName, $uses],
            $stub
        );

        return $this;
    }

    protected function getStub(): string
    {
        return $this->getConfig()['stub'] ?? __DIR__ . '/stubs/controller.stub';
    }

    protected function getDefaultNamespace(): string
    {
        return $this->getConfig()['namespace'] ?? 'App\\Controller';
    }

    protected function getServiceNamespace(): string
    {
        return $this->getContainer()->get(ConfigInterface::class)->get('spider.generator.service')['namespace'] ?? 'App\\Service';
    }

    protected function getRequestNamespace(): string
    {
        return $this->getContainer()->get(ConfigInterface::class)->get('spider.generator.request')['namespace'] ?? 'App\\Request';
    }

    protected function getSuffix(): string
    {
        return $this->getConfig()['suffix'] ?? 'Controller';
    }
}