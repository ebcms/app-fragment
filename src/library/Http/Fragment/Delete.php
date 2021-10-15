<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Fragment;

use App\Ebcms\Admin\Http\Common;
use Ebcms\App;
use Ebcms\Request;
use Psr\SimpleCache\CacheInterface;

class Delete extends Common
{
    public function get(
        App $app,
        Request $request,
        CacheInterface $cache
    ) {
        $cfg_filename = $app->getAppPath() . '/config/' . $request->get('package_name') . '/fragments.php';
        $fragments = is_file($cfg_filename) ? (array)include $cfg_filename : [];
        unset($fragments[$request->get('name')]);

        if (!is_dir(dirname($cfg_filename))) {
            mkdir(dirname($cfg_filename), 0755, true);
        }
        file_put_contents($cfg_filename, '<?php return ' . var_export($fragments, true) . ';');

        $cache->delete(md5('fragment_' . $request->get('name') . '@' . $request->get('package_name')));

        return $this->success('操作成功！');
    }
}
