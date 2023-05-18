<?php

namespace Spider\Commands;

use Hyperf\Command\Annotation\Command;
use Hyperf\Contract\ConfigInterface;

#[Command]
class ServiceCommand extends GeneratorCommand
{
    public function __construct()
    {
        parent::__construct('make:service');
    }

    public function configure()
    {
        $this->setDescription('创建一个新的服务类');

        parent::configure();
    }


    protected function buildClass(string $name): string
    {
        $stub = file_get_contents($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceMapper($stub)->replaceClass($stub, $name);
    }

    protected function replaceMapper(string &$stub)
    {
        $name = $this->input->getOption('mapper') ?? $this->getNameInput();
        $mapperName = $name . 'Mapper';
        $uses = $this->getMapperNamespace() . '\\' . $mapperName;
        $stub = str_replace(
            ['%MAPPER%', '%MAPPER_USES%'],
            [$mapperName, $uses],
            $stub
        );

        return $this;
    }

    protected function getStub(): string
    {
        return $this->getConfig()['stub'] ?? __DIR__ . '/stubs/service.stub';
    }

    protected function getDefaultNamespace(): string
    {
        return $this->getConfig()['namespace'] ?? 'App\\Service';
    }

    protected function getMapperNamespace(): string
    {
        return $this->getContainer()->get(ConfigInterface::class)->get('spider.generator.mapper')['namespace'] ?? 'App\\Mapper';
    }

    protected function getSuffix(): string
    {
        return $this->getConfig()['suffix'] ?? 'Service';
    }
}