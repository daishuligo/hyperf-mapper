<?php

declare(strict_types=1);

namespace Spider;
use Hyperf\Context\Context;
use Spider\Common\SpiderModel;
use Spider\Traits\MapperTrait;

abstract class SpiderMapper
{
    use MapperTrait;

    /**
     * @var SpiderModel
     */
    public $model;

    abstract public function assignModel();

    public function __construct()
    {
        $this->assignModel();
    }

    /**
     * 把数据设置为类属性
     * @param array $data
     */
    public static function setAttributes(array $data)
    {
        Context::set('attributes', $data);
    }

    /**
     * 获取数据
     * @return array
     */
    public function getAttributes(): array
    {
        return Context::get('attributes', []);
    }

    /**
     * 魔术方法，从类属性里获取数据
     * @param string $name
     * @return mixed|string
     */
    public function __get(string $name)
    {
        return $this->getAttributes()[$name] ?? '';
    }
}