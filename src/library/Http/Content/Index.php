<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Content;

use App\Ebcms\Admin\Http\Common;
use Ebcms\Config;
use Ebcms\Request;
use Ebcms\Template;

class Index extends Common
{
    public function get(
        Request $request,
        Config $config,
        Template $template
    ) {
        $fragments = $config->get('fragments@' . $request->get('package_name'), []);
        if (!isset($fragments[$request->get('name')])) {
            return $this->failure('数据不存在~');
        }
        return $this->html($template->renderFromFile('content@ebcms/fragment', [
            'fragment' => $fragments[$request->get('name')]
        ]));
    }
}
