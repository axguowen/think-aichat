# ThinkPHP AI 聊天

一个简单的 ThinkPHP AI 聊天

## 安装
~~~
composer require axguowen/think-aichat
~~~

## 使用

首先配置config目录下的aichat.php配置文件。

~~~php
$aiChat = \think\facade\AiChat::platform('qianfan', [
    'api_key' => 'your api key',
]);
// 发起一次对话请求
$sendResult = $aiChat->send();
var_dump($sendResult);
~~~
