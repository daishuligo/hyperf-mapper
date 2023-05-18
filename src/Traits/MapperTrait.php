<?php

declare(strict_types=1);

namespace Spider\Traits;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Model\Model;
use Spider\SpiderModel;

trait MapperTrait
{
    /**
     * @var SpiderModel
     */
    public $model;

    public function getModel(): SpiderModel
    {
        return new $this->model;
    }

    public function getPageList(?array $params): array
    {
        $paginate = $this->listQuerySetting($params)->paginate(
            $params['page_size'] ?? $this->model::PAGE_SIZE, ['*'], 'page', $params['page'] ?? 1
        );

        return $this->setPaginate($paginate);
    }

    /**
     * 设置查询条件
     * @param array|null $params
     * @return Builder
     */
    public function listQuerySetting(?array $params): Builder
    {
        $query = $this->getModel()->query();

        if ($params['select'] ?? null) {
            $query->select($this->filterQueryAttributes($params['select']));
        }

        $query = $this->handleWith($query, $params);

        $query = $this->handleOrder($query, $params);

        return $this->handleSearch($query, $params);
    }

    /**
     * 过滤查询字段
     * @param Builder $query
     * @param array|null $params
     * @return Builder
     */
    protected function filterQueryAttributes(array $fields, bool $removePk = false): array
    {
        $model = new $this->model;
        $attrs = $model->getFillable();
        foreach ($fields as $key => $field) {
            if (!in_array(trim($field), $attrs) && mb_strpos(str_replace('AS', 'as', $field), 'as') === false) {
                unset($fields[$key]);
            } else {
                $fields[$key] = trim($field);
            }
        }
        if ($removePk && in_array($model->getKeyName(), $fields)) {
            unset($fields[array_search($model->getKeyName(), $fields)]);
        }
        $model = null;
        return ( count($fields) < 1 ) ? ['*'] : $fields;
    }

    /**
     * 处理with数据
     * @param Builder $query
     * @param array|null $params
     * @return Builder
     */
    protected function handleWith(Builder $query, ?array &$params = null): Builder
    {
        if ($params['with'] ?? false) {
            $query->with($params['with']);
        }
        return $query;
    }

    /**
     * 处理排序
     * @param Builder $query
     * @param array|null $params
     * @return Builder
     */
    public function handleOrder(Builder $query, ?array &$params = null): Builder
    {
        // 对树型数据强行加个排序
        if (isset($params['_spider_tree'])) {
            $query->orderBy($params['_spider_tree_pid']);
        }

        if ($params['order_by'] ?? false) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $order) {
                    $query->orderBy($order, $params['order_type'][$key] ?? 'asc');
                }
            } else {
                $query->orderBy($params['order_by'], $params['order_type'] ?? 'asc');
            }
        }

        return $query;
    }

    /**
     * 搜索处理
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query;
    }

    /**
     * 设置分页数据
     * @param LengthAwarePaginatorInterface $paginate
     * @return array
     */
    public function setPaginate(LengthAwarePaginatorInterface $paginate): array
    {
        return [
            'items' => method_exists($this, 'handlePageItems') ? $this->handlePageItems($paginate->items()) : $paginate->items(),
            'pageInfo' => [
                'total' => $paginate->total(),
                'currentPage' => $paginate->currentPage(),
                'totalPage' => $paginate->lastPage()
            ]
        ];
    }

    /**
     * 新增数据
     * @param array $data
     * @return Model|SpiderModel
     */
    public function save(array $data): Model|SpiderModel
    {
        $this->filterExecuteAttributes($data, $this->getModel()->getIncrementing());
        return $this->model::create($data);
    }

    /**
     * 根据主键获取数据
     * @param int $id
     * @return SpiderModel|null
     */
    public function read(int $id): ?SpiderModel
    {
        return ($model = $this->model::find($id)) ? $model : null;
    }

    /**
     * 更新数据
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $this->filterExecuteAttributes($data, true);
        return $this->model::find($id)->update($data) > 0;
    }

    /**
     * 删除数据
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->model::query()->find($id)->delete();
        return true;
    }

    /**
     * 过滤执行字段
     * @param array $data
     * @param bool $removePk
     * @return void
     */
    protected function filterExecuteAttributes(array &$data, bool $removePk = false): void
    {
        $model = new $this->model;
        $attrs = $model->getFillable();
        foreach ($data as $name => $val) {
            if (!in_array($name, $attrs)) {
                unset($data[$name]);
            }
        }
        if ($removePk && isset($data[$model->getKeyName()])) {
            unset($data[$model->getKeyName()]);
        }
        $model = null;
    }
}