<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Fragment;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Fragment\Model\Fragment;
use Ebcms\Request;
use Ebcms\Template;

class Preview extends Common
{
    public function get(
        Fragment $fragmentModel,
        Template $template,
        Request $request
    ) {
        if (!$fragment = $fragmentModel->get($request->get('id'))) {
            return '不存在！';
        }
        $data = [
            'fragment' => $fragment,
            'result' => $fragmentModel->render($request->get('id')),
        ];
        if ($fragment['preview_template']) {
            return $template->renderFromString($fragment['preview_template'], $data);
        } else {
            return $template->renderFromFile('preview@ebcms/fragment', $data);
        }
    }
}
