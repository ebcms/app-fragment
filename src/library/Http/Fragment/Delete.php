<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Fragment;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Fragment\Model\Content;
use App\Ebcms\Fragment\Model\Fragment;
use Ebcms\App;
use Ebcms\Request;

class Delete extends Common
{
    public function get(
        App $app,
        Request $request,
        Content $contentModel,
        Fragment $fragmentModel
    ) {
        $cfg_filename = $app->getAppPath() . '/config/' . $request->get('package_name') . '/fragments.php';
        $fragments = file_exists($cfg_filename) ? (array)include $cfg_filename : [];
        unset($fragments[$request->get('name')]);

        $fragment_id = $fragmentModel->getId($request->get('package_name'), $request->get('name'));
        $contentModel->delete([
            'fragment_id' => $fragment_id,
        ]);

        if (!is_dir(dirname($cfg_filename))) {
            mkdir(dirname($cfg_filename), 0755, true);
        }
        file_put_contents($cfg_filename, '<?php return ' . var_export($fragments, true) . ';');
        $fragmentModel->deleteFragmentCache($fragment_id);

        return $this->success('操作成功！');
    }
}
