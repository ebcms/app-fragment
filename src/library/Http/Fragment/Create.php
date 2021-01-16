<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Fragment;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Fragment\Model\Fragment;
use Ebcms\App;
use Ebcms\Config;
use Ebcms\Router;
use Ebcms\FormBuilder\Builder;
use Ebcms\FormBuilder\Col;
use Ebcms\FormBuilder\Field\Hidden;
use Ebcms\FormBuilder\Field\Number;
use Ebcms\FormBuilder\Field\Text;
use Ebcms\FormBuilder\Field\Textarea;
use Ebcms\FormBuilder\Other\Summernote;
use Ebcms\FormBuilder\Row;
use Ebcms\FormBuilder\Summary;
use Ebcms\RequestFilter;

class Create extends Common
{
    public function get(
        Router $router,
        RequestFilter $input
    ) {

        $form = new Builder('创建碎片');
        $form->addRow(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    (new Hidden('type', $input->get('type'))),
                    (new Text('名称', 'name'))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new Text('标题', 'title'))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new Number('缓存周期', 'ttl'))->set('help', '单位秒')
                ),
                (new Col('col-md-9'))->addItem(
                    ...(function () use ($input, $router): array {
                        $res = [];
                        switch ($input->get('type')) {
                            case 'editor':
                                $res[] = (new Summernote('内容', 'content', '', $router->buildUrl('/ebcms/admin/upload')));
                                break;
                            case 'content':
                                $res[] = (new Textarea('扩展字段', 'fields'))->set('help', '')->set('rows', 5);
                                break;
                        }
                        $res[] = (new Textarea('渲染模板', 'template'))->set('rows', 5)->set('help', '额外支持$fragment变量，内容类型的还支持$contents');
                        $res[] = (new Summary('其他设置'))->addItem(
                            (new Textarea('预览模板', 'preview_template'))->set('rows', 5)->set('help', '额外支持$fragment $result两个变量')
                        );
                        return $res;
                    })()
                )
            )
        );
        return $form;
    }

    public function post(
        App $app,
        Config $config,
        RequestFilter $input,
        Fragment $fragmentModel
    ) {

        $data = [
            'type' => $input->post('type'),
            'title' => $input->post('title'),
            'ttl' => $input->post('ttl', 0, ['intval']),
            'content' => $input->post('content'),
            'fields' => $input->post('fields'),
            'template' => $input->post('template'),
            'preview_template' => $input->post('preview_template'),
        ];

        $fragments = $config->get('fragments@' . $app->getRequestPackage(), []);
        $fragments[$input->post('name')] = $data;

        $cfg_filename = $app->getAppPath() . '/config/' . $app->getRequestPackage() . '/fragments.php';
        if (!is_dir(dirname($cfg_filename))) {
            mkdir(dirname($cfg_filename), 0755, true);
        }
        file_put_contents($cfg_filename, '<?php return ' . var_export($fragments, true) . ';');

        $fragmentModel->deleteFragmentCache($fragmentModel->getId($app->getRequestPackage(), $input->post('name')));
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
