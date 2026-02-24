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

namespace think\aichat;

/**
 * 平台抽象类
 */
abstract class Platform
{
	/**
     * 平台配置参数
     * @var array
     */
	protected $options = [];

	/**
     * 架构函数
     * @access public
     * @param array $options 配置参数
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->setConfig($options);
    }

	/**
     * 动态设置平台配置参数
     * @access public
     * @param array $options 平台配置
     * @return $this
     */
    public function setConfig(array $options)
    {
        // 合并配置
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
        // 返回
        return $this;
    }

    /**
     * 发起一次会话请求
     * @access public
     * @param array $data
     * @return array
     */
    abstract public function send($data);
}
