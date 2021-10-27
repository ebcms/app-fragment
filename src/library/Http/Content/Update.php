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

        if (!isset($fragment['contents'][$request->get('index')])) {
            return $this->failure('数据不存在~');
        }

        $content = $fragment['contents'][$request->get('index')];

        $form = new Builder('编辑内容');
        $form->addRow(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('package_name', $request->get('package_name'))),
                    (new Hidden('name', $request->get('name'))),
                    (new Hidden('index', $request->get('index'))),
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
                                $obj = new $field_class($field, $field, $content[$field] ?? []);
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
        Config $config,
        Request $request
    ) {
        $fragments = $config->get('fragments@' . $request->post('package_name'), []);

        if (!isset($fragments[$request->post('name')])) {
            return $this->failure('数据不存在~');
        }

        $fragment = $fragments[$request->post('name')];

        if (!isset($fragment['contents'][$request->post('index')])) {
            return $this->failure('数据不存在~');
        }

        $content = $fragment['contents'][$request->post('index')];

        $update = $request->post();
        unset($update['package_name']);
        unset($update['name']);
        unset($update['index']);

        $fragment['contents'][$request->post('index')] = $update;
        $fragments[$request->post('name')] = $fragment;

        file_put_contents($app->getAppPath() . '/config/' . $request->post('package_name') . '/fragments.php', '<?php return ' . var_export($fragments, true) . ';');

        $cache->delete(md5('fragment_' . $request->post('name') . '@' . $request->post('package_name')));

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
