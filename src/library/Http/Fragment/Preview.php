<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Fragment;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Fragment\Model\Fragment;
use Ebcms\RequestFilter;
use Ebcms\Template;

class Preview extends Common
{
    public function get(
        Fragment $fragmentModel,
        Template $template,
        RequestFilter $input
    ) {
        if (!$fragment = $fragmentModel->get($input->get('id'))) {
            return '不存在！';
        }
        $data = [
            'fragment' => $fragment,
            'result' => $fragmentModel->render($input->get('id')),
        ];
        if ($fragment['preview_template']) {
            return $template->renderFromString(htmlspecialchars_decode($fragment['preview_template']), $data);
        } else {
            return $template->renderFromFile('preview@ebcms/fragment', $data);
        }
    }
}
