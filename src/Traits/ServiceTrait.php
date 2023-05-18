<?php

declare(strict_types=1);

namespace Spider\Traits;
use Hyperf\DbConnection\Model\Model;
use Spider\Annotation\Transaction;
use Spider\SpiderMapper;

trait ServiceTrait
{
    /**
     * @var SpiderMapper
     */
    public $mapper;

    /**
     * 获取列表
     * @param array|null $params
     * @return array
     */
    public function getPageList(?array $params = []): array
    {
        if ($params['select'] ?? null){
            $params['select'] = explode(',', $params['select']);
        }

        return $this->mapper->getPageList($params);
    }

    /**
     * 新增数据
     * @param array $data
     * @return Model|SpiderModel
     */
    public function save(array $data): Model|SpiderModel
    {
        return $this->mapper->save($data);
    }

    /**
     * 批量新增数据
     * @param array $collects
     * @return bool
     */
    #[Transaction]
    public function batchSave(array $collects): bool
    {
        foreach ($collects as $collect) {
            $this->mapper->save($collect);
        }
        return true;
    }

    /**
     * 读取一条数据
     * @param int $id
     * @return SpiderModel|Model
     */
    public function read(int $id): SpiderModel|Model
    {
        return $this->mapper->read($id);
    }

    /**
     * 更新数据
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->mapper->update($id, $data);
    }

    /**
     * 删除数据
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->mapper->delete($id);
    }
}