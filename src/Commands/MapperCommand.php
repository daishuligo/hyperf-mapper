<?php

namespace Spider\Commands;

use Hyperf\Command\Annotation\Command;
use Hyperf\Contract\ConfigInterface;

#[Command]
class MapperCommand extends GeneratorCommand
{
    public function __construct()
    {
        parent::__construct('make:mapper');
    }

    public function configure()
    {
        $this->setDescription('创建一个新的Mapper类');

        parent::configure();
    }

    protected function buildClass(string $name): string
    {
        $stub = file_get_contents($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceModel($stub)->replaceClass($stub, $name);
    }

    protected function replaceModel(string &$stub)
    {
        $modelName = $this->input->getOption('model') ?? $this->getNameInput();
        $uses = $this->getModelNamespace() . '\\' . $modelName;
        $stub = str_replace(
            ['%MODEL%', '%MODEL_USES%'],
            [$modelName, $uses],
            $stub
        );

        return $this;
    }

    protected function getStub(): string
    {
        return $this->getConfig()['stub'] ?? __DIR__ . '/stubs/mapper.stub';
    }

    protected function getDefaultNamespace(): string
    {
        return $this->getConfig()['namespace'] ?? 'App\\Mapper';
    }

    protected function getModelNamespace(): string
    {
        return $this->getContainer()->get(ConfigInterface::class)->get('spider.generator.mapper')['namespace'] ?? 'App\\Model';
    }

    protected function getSuffix(): string
    {
        return $this->getConfig()['suffix'] ?? 'Mapper';
    }
}