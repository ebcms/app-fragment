<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Fragment;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Admin\Model\Config;
use App\Ebcms\Fragment\Model\Fragment;
use Ebcms\App;
use Ebcms\Router;
use Ebcms\FormBuilder\Builder;
use Ebcms\FormBuilder\Col;
use Ebcms\FormBuilder\Field\Hidden;
use Ebcms\FormBuilder\Field\Number;
use Ebcms\FormBuilder\Field\Text;
use Ebcms\FormBuilder\Field\Textarea;
use Ebcms\FormBuilder\Other\Code;
use Ebcms\FormBuilder\Other\Summernote;
use Ebcms\FormBuilder\Row;
use Ebcms\FormBuilder\Summary;
use Ebcms\RequestFilter;

class Update extends Common
{
    public function get(
        Fragment $fragmentModel,
        Router $router,
        RequestFilter $input
    ) {
        $data = $fragmentModel->get($input->get('id'));

        $disabled = $data['package_name'] != App::getInstance()->getRequestPackage();
        $form = new Builder('更新');
        $form->addRow(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    (new Hidden('id', $data['id'])),
                    (new Hidden('type', $data['type'])),
                    (new Hidden('package_name', $data['package_name'])),
                    (new Text('名称', 'name', $data['name']))->set('help', '一般不超过20个字符')->set('required', 1)->set('readonly', $disabled),
                    (new Text('标题', 'title', $data['title']))->set('help', '一般不超过20个字符')->set('required', 1)->set('disabled', $disabled),
                    (new Number('缓存周期', 'ttl', $data['ttl']))->set('help', '单位秒')
                ),
                (new Col('col-md-9'))->addItem(
                    ...(function () use ($data, $router, $disabled): array {
                        $res = [];
                        switch ($data['type']) {
                            case 'editor':
                                $res[] = (new Summernote('内容', 'content', $data['content'] ?? '', $router->buildUrl('/ebcms/admin/upload')));
                                break;
                            case 'content':
                                if (!$disabled) {
                                    $res[] = (new Textarea('扩展字段', 'fields', $data['fields'] ?? ''))->set('help', '')->set('rows', 5)->set('disabled', $disabled);
                                }
                                break;
                        }
                        $res[] = (new Code('渲染模板', 'template', $data['template'] ?? ''))->set('help', '额外支持$fragment变量，内容类型的还支持$contents');
                        if (!$disabled) {
                            $res[] = (new Code('预览模板(一般使用默认的)', 'preview_template', $data['preview_template']))->set('help', '额外支持$fragment $result两个变量')->set('disabled', $disabled);
                        }
                        return $res;
                    })()
                )
            )
        );
        return $form;
    }

    public function post(
        RequestFilter $input,
        Config $configModel,
        Fragment $fragmentModel
    ) {
        if (!$fragment = $fragmentModel->get($input->post('id'))) {
            return $this->failure('内容不存在！');
        }

        $update = array_intersect_key($input->post(), [
            'type' => '',
            'title' => '',
            'ttl' => '',
            'template' => '',
            'content' => '',
            'fields' => '',
            'preview_template' => '',
        ]);

        list($vendor, $name) = explode('/', $input->post('package_name'));

        $data = [
            $vendor => [
                $name => [
                    'fragments' => [
                        $input->post('name') => $update,
                    ],
                ],
            ],
        ];
        $configModel->save($data);

        $fragmentModel->deleteFragmentCache($fragment['id']);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
