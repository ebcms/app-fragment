<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Content;

use App\Ebcms\Admin\Http\Common;
use Ebcms\App;
use Ebcms\Config;
use Ebcms\Router;
use Ebcms\FormBuilder\Builder;
use Ebcms\FormBuilder\Col;
use Ebcms\FormBuilder\Field\Hidden;
use Ebcms\FormBuilder\Row;
use Ebcms\Request;
use LogicException;
use Psr\SimpleCache\CacheInterface;

class Create extends Common
{
    public function get(
        Config $config,
        Request $request,
        Router $router
    ) {
        $fragments = $config->get('fragments@' . $request->get('package_name'), []);

        if (!isset($fragments[$request->get('name')])) {
            return $this->failure('数据不存在~');
        }

        $fragment = $fragments[$request->get('name')];

        $content = $fragment['contents'][$request->get('index', -1)] ?? [];

        $form = new Builder('创建内容');
        $form->addRow(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('package_name', $request->get('package_name'))),
                    (new Hidden('name', $request->get('name'))),
                    ...(function () use ($router, $fragment, $content): array {
                        $res = [];
                        foreach (array_filter(explode(PHP_EOL, $fragment['fields'] ?? '')) as $val) {

                            list($field, $type, $help, $ext) = explode(',', trim($val) . ',,,,');
                            $type = $type ?: 'Input';
                            $help = $help ?: '';
                            $ext = $ext ?: '';

                            $field_class = 'Ebcms\\FormBuilder\\Field\\' . $type;
                            if (!class_exists($field_class)) {
                                throw new LogicException('类型' . $type . '不支持');
                            }

                            if (in_array($type, ['Checkbox'])) {
                                $obj = new $field_class($field, $field . '[]', $content[$field] ?? []);
                            } else {
                                $obj = new $field_class($field, $field, $content[$field] ?? '');
                            }
                            $obj->set('help', $help);
                            $obj->set('upload_url', $router->buildUrl('/ebcms/admin/upload'));
                            $obj->set('items', array_combine(explode('|', $ext), explode('|', $ext)));
                            $res[] = $obj;
                        }
                        return $res;
                    })()
                ),
                (new Col('col-md-3'))->addItem()
            )
        );
        return $form;
    }

    public function post(
        App $app,
        CacheInterface $cache,
        Request $request,
        Config $config
    ) {

        $fragments = $config->get('fragments@' . $request->post('package_name'), []);
        if (!isset($fragments[$request->post('name')])) {
            return $this->failure('数据不存在~');
        }
        $fragments[$request->post('name')]['contents'] = $fragments[$request->post('name')]['contents']  ?? [];

        $content = $request->post();
        unset($content['package_name']);
        unset($content['name']);
        $fragments[$request->post('name')]['contents'][] = $content;
        $fragments[$request->post('name')]['contents'] = array_values($fragments[$request->post('name')]['contents']);

        file_put_contents($app->getAppPath() . '/config/' . $request->post('package_name') . '/fragments.php', '<?php return ' . var_export($fragments, true) . ';');

        $cache->delete(md5('fragment_' . $request->post('name') . '@' . $request->post('package_name')));

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
