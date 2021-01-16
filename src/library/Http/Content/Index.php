<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Content;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Fragment\Model\Content;
use Ebcms\RequestFilter;
use Ebcms\Template;

class Index extends Common
{

    public function get(
        RequestFilter $input,
        Template $template,
        Content $contentModel
    ) {
        $options = [
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ];
        if ($input->get('fragment_id')) {
            $options['fragment_id'] = $input->get('fragment_id');
        }
        $data = $contentModel->select('*', $options);

        return $this->html($template->renderFromFile('content@ebcms/fragment', [
            'data' => $data,
            'contentModel' => $contentModel
        ]));
    }
}
