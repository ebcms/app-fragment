<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Fragment;

use App\Ebcms\Admin\Http\Common;
use Ebcms\App;
use Ebcms\Config;
use Ebcms\Router;
use Ebcms\FormBuilder\Builder;
use Ebcms\FormBuilder\Col;
use Ebcms\FormBuilder\Field\Hidden;
use Ebcms\FormBuilder\Field\Input;
use Ebcms\FormBuilder\Field\Textarea;
use Ebcms\FormBuilder\Field\Code;
use Ebcms\FormBuilder\Field\Cover;
use Ebcms\FormBuilder\Field\Summernote;
use Ebcms\FormBuilder\Row;
use Ebcms\Request;
use Psr\SimpleCache\CacheInterface;

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
                    (new Input('名称', 'name'))->set('help', '一般不超过20个字符')->set('attr.required', 1),
                    (new Cover('截图', 'cover', '', $router->buildUrl('/ebcms/admin/upload'))),
                    (new Input('缓存周期', 'ttl', 3600))->set('help', '单位秒')->set('attr.type', 'number')
                ),
                (new Col('col-md-9'))->addItem(
                    ...(function () use ($request, $router): array {
                        $res = [];
                        switch ($request->get('type')) {
                            case 'editor':
                                $res[] = new Summernote('内容', 'content', '', $router->buildUrl('/ebcms/admin/upload'));
                                break;
                            case 'content':
                                $res[] = (new Textarea('扩展字段', 'fields'))->set('help', "一行一个 格式：字段名称,字段类型,帮助文本,其他信息 例如：标题,Input,不超过80个字符 支持的字段类型有：Input,Textarea,Cover,Pics,Upload,Files,Code,Select,Checkbox,Radio等等")->set('attr.rows', 5);
                                $res[] = (new Code('渲染模板', 'template'))->set('help', '支持$contents|$content等变量');
                                break;
                            case 'template':
                                $res[] = (new Code('渲染模板', 'template'))->set('help', '支持$contents|$content等变量');
                                break;
                        }
                        return $res;
                    })()
                )
            )
        );
        return $form;
    }

    public function post(
        App $app,
        CacheInterface $cache,
        Config $config,
        Request $request
    ) {

        $data = [
            'type' => $request->post('type'),
            'cover' => $request->post('cover'),
            'ttl' => $request->post('ttl', 0, ['intval']),
            'content' => $request->post('content'),
            'fields' => $request->post('fields'),
            'contents' => [],
            'template' => $request->post('template'),
        ];

        $fragments = $config->get('fragments@' . $app->getRequestPackage(), []);
        $fragments[$request->post('name')] = $data;

        $cfg_filename = $app->getAppPath() . '/config/' . $app->getRequestPackage() . '/fragments.php';
        if (!is_dir(dirname($cfg_filename))) {
            mkdir(dirname($cfg_filename), 0755, true);
        }
        file_put_contents($cfg_filename, '<?php return ' . var_export($fragments, true) . ';');

        $cache->delete(md5('fragment_' . $request->post('name') . '@' . $request->post('package_name')));

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
