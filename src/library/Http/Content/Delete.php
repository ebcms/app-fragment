<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Content;

use App\Ebcms\Admin\Http\Common;
use Ebcms\App;
use Ebcms\Config;
use Ebcms\Request;
use Psr\SimpleCache\CacheInterface;

class Delete extends Common
{
    public function get(
        App $app,
        Request $request,
        Config $config,
        CacheInterface $cache
    ) {
        $fragments = $config->get('fragments@' . $request->get('package_name'), []);
        if (!isset($fragments[$request->get('name')])) {
            return $this->failure('数据不存在~');
        }
        $fragment = $fragments[$request->get('name')];
        $fragment['contents'] = $fragment['contents'] ?? [];
        unset($fragment['contents'][intval($request->get('index'))]);
        $fragment['contents'] = array_values($fragment['contents']);
        $fragments[$request->get('name')] = $fragment;

        file_put_contents($app->getAppPath() . '/config/' . $request->get('package_name') . '/fragments.php', '<?php return ' . var_export($fragments, true) . ';');

        $cache->delete(md5('fragment_' . $request->get('name') . '@' . $request->get('package_name')));

        return $this->success('操作成功！');
    }
}
