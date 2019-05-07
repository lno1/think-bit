<?php

namespace think\bit\traits;

use think\Db;
use think\db\Query;
use think\Exception;
use think\bit\validate;

/**
 * Trait OriginListsModel
 * @package think\bit\traits
 * @property string model 模型名称
 * @property array post POST请求
 * @property array lists_origin_before_result 前置返回结果
 * @property array lists_origin_condition 固定条件
 * @property array lists_origin_or_condition 列表数据或条件
 * @property array lists_origin_field 固定返回字段
 * @property string lists_origin_orders 排序设定
 */
trait OriginListsModel
{
    public function originLists()
    {
        // 通用验证
        $validate = new validate\Lists;
        if (!$validate->scene('origin')->check($this->post)) return [
            'error' => 1,
            'msg' => $validate->getError()
        ];

        if (method_exists($this, '__originListsBeforeHooks')) {
            $before_result = $this->__originListsBeforeHooks();
            if (!$before_result) return $this->lists_origin_before_result;
        }

        try {
            // 是否存在条件
            $condition = $this->lists_origin_condition;
            if (isset($this->post['where'])) $condition = array_merge(
                $condition, $this->post['where']
            );

            $or = $this->lists_origin_or_condition;
            if (isset($this->post['or'])) $condition = array_merge(
                $or,
                $this->post['or']
            );

            // 模糊搜索
            $like = function (Query $query) {
                if (isset($this->post['like'])) foreach ($this->post['like'] as $key => $like) {
                    if (empty($like['value'])) continue;
                    $query->where($like['field'], 'like', '%' . (string)$like['value'] . '%');
                }
            };

            // 执行查询
            $lists = Db::name($this->model)
                ->where($condition)
                ->where($like)
                ->whereOr($or)
                ->field($this->lists_origin_field[0], $this->lists_origin_field[1])
                ->order($this->lists_origin_orders)
                ->select();

            // 是否自定义返回
            if (method_exists($this, '__originListsCustomReturn')) {
                return $this->__originListsCustomReturn($lists);
            } else {
                return [
                    'error' => 0,
                    'data' => $lists
                ];
            }
        } catch (Exception $e) {
            return [
                'error' => 1,
                'msg' => (string)$e->getMessage()
            ];
        }
    }
}