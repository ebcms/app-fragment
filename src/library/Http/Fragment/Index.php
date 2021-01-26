<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Fragment;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Fragment\Model\Content;
use App\Ebcms\Fragment\Model\Fragment;
use Ebcms\App;
use Ebcms\Template;

class Index extends Common
{

    public function get(
        Template $template,
        Fragment $fragmentModel,
        Content $contentModel
    ) {
        $fragments = [];
        $fragments[App::getInstance()->getRequestPackage()] = [];
        foreach ($fragmentModel->all() as $value) {
            if (!isset($fragments[$value['package_name']])) {
                $fragments[$value['package_name']] = [];
            }

            if ($value['type'] == 'content') {
                $value['content_count'] = $contentModel->count([
                    'fragment_id' => $value['id'],
                ]);
            }

            $fragments[$value['package_name']][] = $value;
        }
        if (!$fragments[App::getInstance()->getRequestPackage()]) {
            unset($fragments[App::getInstance()->getRequestPackage()]);
        }
        return $this->html($template->renderFromFile('fragment@ebcms/fragment', [
            'fragments' => $fragments,
        ]));
    }
}
