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
use Ebcms\FormBuilder\Other\Code;
use Ebcms\FormBuilder\Other\Summernote;
use Ebcms\FormBuilder\Other\Tab;
use Ebcms\FormBuilder\Row;
use Ebcms\Request;

class Create extends Common
{
    public function get(
        Router $router,
        Request $request
    ) {

        $form = new Builder('创建碎片');
        $form->addRow(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    (new Hidden('type', $request->get('type'))),
                    (new Text('名称', 'name'))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new Text('标题', 'title'))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new Number('缓存周期', 'ttl'))->set('help', '单位秒')
                ),
                (new Col('col-md-9'))->addItem(
                    ...(function () use ($request, $router): array {
                        $tab = new Tab();
                        switch ($request->get('type')) {
                            case 'editor':
                                $tab->addTab('内容', (new Summernote('内容', 'content', '', $router->buildUrl('/ebcms/admin/upload'))));
                                break;
                            case 'content':
                                $tab->addTab('扩展字段', (new Textarea('扩展字段', 'fields'))->set('help', '')->set('rows', 5));
                                break;
                        }

                        $tab->addTab('渲染模板', (new Code('渲染模板', 'template'))->set('help', '额外支持$fragment变量，内容类型的还支持$contents'));
                        $tab->addTab('预览模板', (new Code('预览模板(一般使用默认的)', 'preview_template'))->set('help', '额外支持$fragment $result两个变量'));
                        return [$tab];
                    })()
                )
            )
        );
        return $form;
    }

    public function post(
        App $app,
        Config $config,
        Request $request,
        Fragment $fragmentModel
    ) {

        $data = [
            'type' => $request->post('type'),
            'title' => $request->post('title'),
            'ttl' => $request->post('ttl', 0, ['intval']),
            'content' => $request->post('content'),
            'fields' => $request->post('fields'),
            'template' => $request->post('template'),
            'preview_template' => $request->post('preview_template'),
        ];

        $fragments = $config->get('fragments@' . $app->getRequestPackage(), []);
        $fragments[$request->post('name')] = $data;

        $cfg_filename = $app->getAppPath() . '/config/' . $app->getRequestPackage() . '/fragments.php';
        if (!is_dir(dirname($cfg_filename))) {
            mkdir(dirname($cfg_filename), 0755, true);
        }
        file_put_contents($cfg_filename, '<?php return ' . var_export($fragments, true) . ';');

        $fragmentModel->deleteFragmentCache($fragmentModel->getId($app->getRequestPackage(), $request->post('name')));
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
