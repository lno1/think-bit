<?php
declare (strict_types=1);

namespace think\bit;

use Closure;
use think\App;
use think\Request;

/**
 * CURD 抽象类
 * Class CurdController
 * @package think\bit\common
 */
abstract class CurdController
{
    /**
     * Request实例
     * @var Request
     */
    protected $request;

    /**
     * 应用实例
     * @var App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 模型名称
     * @var string
     */
    protected $model;

    /**
     * 请求body
     * @var array
     */
    protected $post = [];

    /**
     * 默认列表数据验证
     * @var array
     */
    protected $origin_lists_default_validate = [
        'where' => 'array'
    ];

    /**
     * 默认前置返回结果
     * @var array
     */
    protected $origin_lists_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * 列表查询条件
     * @var array
     */
    protected $origin_lists_condition = [];

    /**
     * 列表查询闭包条件
     * @var Closure|null
     */
    protected $origin_lists_condition_query = null;

    /**
     * 列表数据排序
     * @var array
     */
    protected $origin_lists_orders = ['create_time' => 'desc'];

    /**
     * 列表数据指定返回字段
     * @var array
     */
    protected $origin_lists_field = [];

    /**
     * 列表数据指定排除的返回字段
     * @var array
     */
    protected $origin_lists_without_field = ['update_time', 'create_time'];

    /**
     * 分页数据默认验证器
     * @var array
     */
    protected $lists_default_validate = [
        'page' => 'require',
        'page.limit' => 'require|number|between:1,50',
        'page.index' => 'require|number|min:1',
        'where' => 'array'
    ];

    /**
     * 分页数据前置返回结果
     * @var array
     */
    protected $lists_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * 分页数据查询条件
     * @var array
     */
    protected $lists_condition = [];

    /**
     * 分页数据查询闭包条件
     * @var Closure|null
     */
    protected $lists_condition_query = null;

    /**
     * 分页数据排序
     * @var array
     */
    protected $lists_orders = ['create_time' => 'desc'];

    /**
     * 分页数据指定返回字段
     * @var array
     */
    protected $lists_field = [];

    /**
     * 分页数据指定排除的返回字段
     * @var array
     */
    protected $lists_without_field = ['update_time', 'create_time'];

    /**
     * 单条数据默认验证器
     * @var array
     */
    protected $get_default_validate = [
        'id' => 'requireWithout:where|number',
        'where' => 'requireWithout:id|array'
    ];

    /**
     * 单条数据前置返回结果
     * @var array
     */
    protected $get_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * 单条数据查询条件
     * @var array
     */
    protected $get_condition = [];

    /**
     * 单条数据指定返回字段
     * @var array
     */
    protected $get_field = [];

    /**
     * 单条数据指定排除的返回字段
     * @var array
     */
    protected $get_without_field = ['update_time', 'create_time'];

    /**
     * 分离新增模型名称
     * @var string
     */
    protected $add_model;

    /**
     * 新增数据默认验证器
     * @var array
     */
    protected $add_default_validate = [];

    /**
     * 自动更新时间戳
     * @var bool
     */
    protected $add_auto_timestamp = true;

    /**
     * 新增数据前置返回结果
     * @var array
     */
    protected $add_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * 新增数据后置返回结果
     * @var array
     */
    protected $add_after_result = [
        'error' => 1,
        'msg' => 'error:after_fail'
    ];

    /**
     * 新增数据失败返回结果
     * @var array
     */
    protected $add_fail_result = [
        'error' => 1,
        'msg' => 'error:insert_fail'
    ];

    /**
     * 分离修改模型名称
     * @var string
     */
    protected $edit_model;

    /**
     * 编辑默认验证器
     * @var array
     */
    protected $edit_default_validate = [
        'id' => 'require|number',
        'switch' => 'require|bool'
    ];

    /**
     * 自动更新时间戳
     * @var bool
     */
    protected $edit_auto_timestamp = true;

    /**
     * 是否仅为状态编辑
     * @var bool
     */
    protected $edit_switch = false;

    /**
     * 编辑前置返回结果
     * @var array
     */
    protected $edit_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * 编辑查询条件
     * @var array
     */
    protected $edit_condition = [];

    /**
     * 编辑失败返回结果
     * @var array
     */
    protected $edit_fail_result = [
        'error' => 1,
        'msg' => 'error:fail'
    ];

    /**
     * 编辑后置返回结果
     * @var array
     */
    protected $edit_after_result = [
        'error' => 1,
        'msg' => 'error:after_fail'
    ];

    /**
     * 分离删除模型名称
     * @var string
     */
    protected $delete_model;

    /**
     * 删除默认验证器
     * @var array
     */
    protected $delete_default_validate = [
        'id' => 'require'
    ];

    /**
     * 删除前置返回结果
     * @var array
     */
    protected $delete_before_result = [
        'error' => 1,
        'msg' => 'error:before_fail'
    ];

    /**
     * 删除查询条件
     * @var array
     */
    protected $delete_condition = [];

    /**
     * 删除处理事务开始之后数据写入之前返回结果
     * @var array
     */
    protected $delete_prep_result = [
        'error' => 1,
        'msg' => 'error:prep_fail'
    ];

    /**
     * 删除失败返回结果
     * @var array
     */
    protected $delete_fail_result = [
        'error' => 1,
        'msg' => 'error:fail'
    ];

    /**
     * 删除后置返回结果
     * @var array
     */
    protected $delete_after_result = [
        'error' => 1,
        'msg' => 'error:after_fail'
    ];

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {

    }
}