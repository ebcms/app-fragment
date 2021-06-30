<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Content;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Fragment\Model\Content;
use App\Ebcms\Fragment\Model\Fragment;
use Ebcms\Request;

class Priority extends Common
{
    public function post(
        Request $request,
        Fragment $fragmentModel,
        Content $contentModel
    ) {
        $type = $request->post('type');
        $content = $contentModel->get('*', [
            'id' => $request->post('id'),
        ]);

        $contents = $contentModel->select('*', [
            'fragment_id' => $content['fragment_id'],
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $count = $contentModel->count([
            'fragment_id' => $content['fragment_id'],
            'id[!]' => $content['id'],
            'priority[<=]' => $content['priority'],
            'ORDER' => [
                'priority' => 'DESC',
                'id' => 'ASC',
            ],
        ]);
        $change_key = $type == 'up' ? $count + 1 : $count - 1;

        if ($change_key < 0) {
            return $this->failure('已经是最有一位了！');
        }
        if ($change_key > count($contents) - 1) {
            return $this->failure('已经是第一位了！');
        }
        $contents = array_reverse($contents);
        foreach ($contents as $key => $value) {
            if ($key == $change_key) {
                $contentModel->update([
                    'priority' => $count,
                ], [
                    'id' => $value['id'],
                ]);
            } elseif ($key == $count) {
                $contentModel->update([
                    'priority' => $change_key,
                ], [
                    'id' => $value['id'],
                ]);
            } else {
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
