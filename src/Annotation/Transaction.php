<?php

namespace Spider\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_METHOD)]
class Transaction extends AbstractAnnotation
{
    /**
     * retry 重试次数
     * @var int
     */
    public int $retry = 1;

    public string $connection = 'default';

    public function __construct($connection = 'default', $value = 1)
    {
        parent::__construct($value);
        $this->bindMainProperty('retry', [ $value ]);
        $this->bindMainProperty('connection', [ $connection ]);
    }
}