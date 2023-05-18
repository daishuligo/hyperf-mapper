<?php

declare(strict_types=1);

namespace Spider;

use Hyperf\DbConnection\Model\Model;

abstract class SpiderModel extends Model
{
    public const PAGE_SIZE = 15;
}