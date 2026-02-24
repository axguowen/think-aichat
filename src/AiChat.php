<?php
// +----------------------------------------------------------------------
// | ThinkPHP AI Chat [Simple AI Chat For ThinkPHP]
// +----------------------------------------------------------------------
// | ThinkPHP AI 聊天
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: axguowen <axguowen@qq.com>
// +----------------------------------------------------------------------

namespace think;

use think\helper\Arr;
use think\exception\InvalidArgumentException;

/**
 * AI聊天
 */
class AiChat extends Manager
{
    /**
     * 驱动的命名空间
     * @var string
     */
	protected $namespace = '\\think\\aichat\\driver\\';

	/**
     * 默认驱动
     * @access public
     * @return string|null
     */
    public function getDefaultDriver()
    {
        return $this->getConfig('default');
    }

	/**
     * 获取客户端配置
     * @access public
     * @param null|string $name 配置名称
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getConfig($name = null, $default = null)
    {
        if (!is_null($name)) {
            return $this->app->config->get('aichat.' . $name, $default);
        }

        return $this->app->config->get('aichat');
    }

	/**
     * 获取平台配置
     * @param string $platform 平台名称
     * @param null|string $name 配置名称
     * @param null|string $default 默认值
     * @return array
     */
    public function getPlatformConfig($platform, $name = null, $default = null)
    {
		// 读取驱动配置文件
        if ($config = $this->getConfig('platforms.' . $platform)) {
            return Arr::get($config, $name, $default);
        }
		// 驱动不存在
        throw new \InvalidArgumentException('平台 [' . $platform . '] 配置不存在.');
    }

    /**
     * 当前平台的驱动配置
     * @param string $name 驱动名称
     * @return mixed
     */
    protected function resolveType($name)
    {
        return $this->getPlatformConfig($name, 'type', 'deepseek');
    }

	/**
     * 获取驱动配置
     * @param string $name 驱动名称
     * @return mixed
     */
    protected function resolveConfig($name)
    {
        return $this->getPlatformConfig($name);
    }

	/**
     * 选择或者切换平台
     * @access public
     * @param string $name 平台的配置名
     * @param array $options 平台配置
     * @return \think\aichat\Platform
     */
    public function platform($name = null, array $options = [])
    {
        // 如果指定了自定义配置
        if(!empty($options)){
            // 创建驱动实例并设置参数
            return $this->createDriver($name)->setConfig($options);
        }
        // 返回已有驱动实例
        return $this->driver($name);
    }

	/**
     * 发起一次对话请求
     * @access public
     * @param array $data 转化数据
     * @param array $options 配置参数
     * @return array
     */
    public function send($data, $options = [])
    {
        return $this->platform(null, $options)->send($data);
    }
}
