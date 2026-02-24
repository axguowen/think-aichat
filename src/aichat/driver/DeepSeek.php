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

namespace think\aichat\driver;

use think\aichat\Platform;
use axguowen\HttpClient;

/**
 * DeepSeek平台
 * @document https://platform.deepseek.com/
 */
class DeepSeek extends Platform
{
    /**
     * 基础URL
     * @const
     */
    const BASE_URL = 'https://api.deepseek.com/v1';

	/**
     * 平台配置参数
     * @var array
     */
    protected $options = [
        // API密钥
        'api_key' => '',
        // 应用ID
        'app_id' => '',
        // 模型
        'model' => '',
        // 随机度
        'temperature' => 0.7,
        // 最大返回token数
        'max_tokens' => 1024,
    ];

	/**
     * 发起一次会话请求
     * @access public
     * @param array $data 请求数据
     * @return array
     */
	public function send(array $data)
	{
        // 如果API密钥为空
        if(empty($this->options['api_key'])){
            return [null, new \Exception('未指定API密钥', 400)];
        }

        // 如果没有聊天上下文信息
        if(!isset($data['messages']) || empty($data['messages'])){
            return [null, new \Exception('未指定聊天上下文信息', 400)];
        }

        // 如果没有指定模型
        if(!isset($data['model']) || empty($data['model'])){
            $data['model'] = $this->options['model'];
        }

        // 如果没有指定随机度
        if(!isset($data['temperature']) || empty($data['temperature'])){
            $data['temperature'] = $this->options['temperature'];
        }

        // 如果没有指定最大返回token数
        if(!isset($data['max_tokens']) || empty($data['max_tokens'])){
            $data['max_tokens'] = $this->options['max_tokens'];
        }

        // 发送请求并返回结果
        return $this->sendRequest($data, '/chat/completions');
	}

    /**
     * 发送请求
     * @access protected
     * @param array $data 转化数据
     * @param string $path URL
     * @return array
     */
	protected function sendRequest(array $data, $path = '')
	{
        // json序列化后的数据
        $requestJson = json_encode($data);

        try{
            // 发送请求
            $response = HttpClient::post(static::BASE_URL . $path, $requestJson, [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . trim(preg_replace('/^Bearer\s*/i', '', $this->options['api_key'])),
            ]);
            // 请求失败
            if (!$response->ok()) {
                return [null, new \Exception($response->error, 400)];
            }
            // 获取请求结果
            $result = is_null($response->body) ? [] : $response->json();
            // 如果为空
            if(empty($result)){
                // 返回
                return [null, new \Exception('请求失败, 返回数据为空', 400)];
            }
            // 如果请求错误
            if(isset($result['code']) && !empty($result['code'])){
                return [null, new \Exception('操作失败, 错误信息: ' . $result['message'], 400)];
            }
            // 返回
            return [$result, null];
        }
        // 异常捕获
        catch (\Exception $e) {
            // 如果开启调试模式
            if(\think\facade\App::isDebug()){
                // 手动抛出异常
                throw $e;
            }
            return [null, $e];
        }
    }
}