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

class Update extends Common
{
    public function get(
        Config $config,
        Router $router,
        Request $request
    ) {

        $fragments = $config->get('fragments@' . $request->get('package_name'), []);
        if (!isset($fragments[$request->get('name')])) {
            return $this->failure('数据不存在~');
        }
        $fragment = $fragments[$request->get('name')];

        $form = new Builder('编辑');
        $form->addRow(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    (new Hidden('package_name', $request->get('package_name'))),
                    (new Input('名称', 'name', $request->get('name')))->set('help', '一般不超过20个字符')->set('required', 1)->set('attr.readonly', 'readonly'),
                    (new Cover('截图', 'cover', $fragment['cover'] ?? '', $router->buildUrl('/ebcms/admin/upload'))),
                    (new Input('缓存周期', 'ttl', $fragment['ttl'] ?? 3600, 'number'))->set('help', '单位秒')
                ),
                (new Col('col-md-9'))->addItem(
                    ...(function () use ($fragment, $router): array {
                        $res = [];
                        switch ($fragment['type']) {
                            case 'editor':
                                $res[] = new Summernote('内容', 'content', $fragment['content'] ?? '', $router->buildUrl('/ebcms/admin/upload'));
                                break;
                            case 'content':
                                $res[] = (new Textarea('扩展字段', 'fields', $fragment['fields'] ?? ''))->set('help', "一行一个 格式：字段,类型,帮助文本,其他信息 例如：标题,Input,不超过80个字符 支持的字段类型有：Input,Textarea,Cover,Pics,Upload,Files,Code,Select,Checkbox,Radio等等")->set('attr.rows', 5)->set('attr.spellcheck', "false");
                                $res[] = (new Code('渲染模板', 'template', $fragment['template'] ?? ''))->set('help', '支持$contents|$content等变量');
                                break;
                            case 'template':
                                $res[] = (new Code('渲染模板', 'template', $fragment['template'] ?? ''))->set('help', '支持$contents|$content等变量');
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
        Request $request,
        Config $config,
        CacheInterface $cache
    ) {
        $fragments = $config->get('fragments@' . $request->post('package_name'));

        if (!isset($fragments[$request->post('name')])) {
            return $this->failure('内容不存在~');
        }

        $fragments[$request->post('name')] = array_merge($fragments[$request->post('name')], array_intersect_key($request->post(), [
            'cover' => '',
            'ttl' => '',
            'template' => '',
            'content' => '',
            'fields' => '',
        ]));

        file_put_contents($app->getAppPath() . '/config/' . $request->post('package_name') . '/fragments.php', '<?php return ' . var_export($fragments, true) . ';');

        $cache->delete(md5('fragment_' . $request->post('name') . '@' . $request->post('package_name')));

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
