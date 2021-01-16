<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Content;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Fragment\Model\Content;
use App\Ebcms\Fragment\Model\Fragment;
use Ebcms\RequestFilter;

class Delete extends Common
{
    public function get(
        RequestFilter $input,
        Fragment $fragmentModel,
        Content $contentModel
    ) {
        $content = $contentModel->get('*', [
            'id' => $input->get('id'),
        ]);
        $contentModel->delete([
            'id' => $input->get('id'),
        ]);
        $contents = array_reverse($contentModel->select('*', [
            'fragment_id' => $content['fragment_id'],
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ]));
        foreach ($contents as $key => $value) {
            if ($key != $value['priority']) {
                $contentModel->update([
                    'priority' => $key,
                ], [
                    'id' => $value['id'],
                ]);
            }
        }

        $fragmentModel->deleteFragmentCache($content['fragment_id']);

        return $this->success('操作成功！');
    }
}
