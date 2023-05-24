<?php

namespace Spider;

use Hyperf\Collection\Arr;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Validation\Request\FormRequest;
use Spider\Util\SpiderUtil;

abstract class SpiderFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function commonRules(): array
    {
        return [];
    }

    public function rules(): array
    {
        $operation = $this->getOperation();
        $method = $operation . 'Rules';
        $rules = ( $operation && method_exists($this, $method) ) ? $this->$method() : [];
        return array_merge($rules, $this->commonRules());
    }

    public function only(array $keys)
    {
        return Arr::only($this->all(), $keys);
    }

    protected function getOperation(): ?string
    {
        $controllerMethod = $this->getAttribute(Dispatched::class)->handler->callback;
        return $controllerMethod[1] ?? '';
    }

    protected function getLanguage(): ?string
    {
        return SpiderUtil::getLanguage();
    }
}