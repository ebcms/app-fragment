<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Content;

use App\Ebcms\Admin\Http\Common;
use Ebcms\App;
use Ebcms\Config;
use Ebcms\Request;
use Psr\SimpleCache\CacheInterface;

class Priority extends Common
{
    public function post(
        App $app,
        CacheInterface $cache,
        Config $config,
        Request $request
    ) {

        $fragments = $config->get('fragments@' . $request->post('package_name'), []);
        if (!isset($fragments[$request->post('name')])) {
            return $this->failure('数据不存在~');
        }

        $type = $request->post('type');
        $index = $request->post('index');
        $fragment = $fragments[$request->post('name')];
        $contents = array_values($fragment['contents'] ?? []);
        switch ($type) {
            case 'up':
                if ($index <= 0) {
                    return $this->failure('已经在最顶部了~');
                }
                $tmp = $contents[$index];
                $contents[$index] = $contents[$index - 1];
                $contents[$index - 1] = $tmp;
                break;

            case 'down':
                if ($index >= count($contents) - 1) {
                    return $this->failure('已经在最底部了~');
                }
                $tmp = $contents[$index];
                $contents[$index] = $contents[$index + 1];
                $contents[$index + 1] = $tmp;
                break;

            default:
                return $this->failure('参数错误~');
                break;
        }
        $fragment['contents'] = $contents;
        $fragments[$request->post('name')] = $fragment;

        file_put_contents($app->getAppPath() . '/config/' . $request->post('package_name') . '/fragments.php', '<?php return ' . var_export($fragments, true) . ';');

        $cache->delete(md5('fragment_' . $request->post('name') . '@' . $request->post('package_name')));

        return $this->success('操作成功！');
    }
}
