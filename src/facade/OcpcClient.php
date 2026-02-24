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

namespace think\facade;

use think\Facade;

/**
 * @see \think\AiChat
 * @mixin \think\AiChat
 */
class AiChat extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'think\AiChat';
    }
}
