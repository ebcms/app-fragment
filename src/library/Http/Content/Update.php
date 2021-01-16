<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Content;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Fragment\Model\Fragment;
use App\Ebcms\Fragment\Model\Content;
use Ebcms\Router;
use LogicException;
use Ebcms\FormBuilder\Builder;
use Ebcms\FormBuilder\Col;
use Ebcms\FormBuilder\Field\Checkbox;
use Ebcms\FormBuilder\Field\Hidden;
use Ebcms\FormBuilder\Field\Radio;
use Ebcms\FormBuilder\Field\Select;
use Ebcms\FormBuilder\Field\Text;
use Ebcms\FormBuilder\Field\Textarea;
use Ebcms\FormBuilder\Field\Url;
use Ebcms\FormBuilder\Other\Cover;
use Ebcms\FormBuilder\Other\Files;
use Ebcms\FormBuilder\Other\Pics;
use Ebcms\FormBuilder\Other\SimpleMDE;
use Ebcms\FormBuilder\Other\Summernote;
use Ebcms\FormBuilder\Other\TextUpload;
use Ebcms\FormBuilder\Row;
use Ebcms\RequestFilter;

class Update extends Common
{
    public function get(
        Content $contentModel,
        Fragment $fragmentModel,
        Router $router,
        RequestFilter $input
    ) {
        $data = $contentModel->get('*', [
            'id' => $input->get('id', 0, ['intval']),
        ]);

        if (!$fragment = $fragmentModel->get($data['fragment_id'])) {
            return $this->failure('碎片不存在！');
        }

        $form = new Builder('编辑内容');
        $form->addRow(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('id', $data['id'])),
                    (new Text('标题', 'title', $data['title']))->set('help', '一般不超过80个字符')->set('required', 1),
                    (new Url('跳转地址', 'redirect_uri', $data['redirect_uri']))->set('required', 1),
                    (new Textarea('简介', 'description', $data['description'])),
                    ...(function () use ($router, $fragment, $data): array {
                        $res = [];
                        $extra = unserialize($data['extra']);
                        // 自定义字段
                        foreach (array_filter(explode(PHP_EOL, htmlspecialchars_decode($fragment['fields'] ?? ''))) as $val) {
                            $field = [];
                            foreach (array_filter(explode(';', trim($val))) as $tmp) {
                                list($k, $v) = explode('=', trim($tmp));
                                $field[trim($k)] = trim($v);
                            }
                            if (!isset($field['name']) || !isset($field['label']) || !isset($field['type'])) {
                                throw new LogicException('扩展字段每一行的name label type必须设置');
                            }
                            switch ($field['type']) {
                                case 'summernote':
                                    $field['upload_url'] = $field['upload_url'] ?? $router->buildUrl('/ebcms/admin/upload');
                                    $tmp = (new Summernote($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;
                                case 'simplemde':
                                    $field['upload_url'] = $field['upload_url'] ?? $router->buildUrl('/ebcms/admin/upload');
                                    $tmp = (new SimpleMDE($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;
                                case 'text':
                                    $tmp = (new Text($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;
                                case 'textarea':
                                    $tmp = (new Textarea($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;
                                case 'cover':
                                    $field['upload_url'] = $field['upload_url'] ?? $router->buildUrl('/ebcms/admin/upload');
                                    $tmp = (new Cover($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;
                                case 'pics':
                                    $field['upload_url'] = $field['upload_url'] ?? $router->buildUrl('/ebcms/admin/upload');
                                    $tmp = (new Pics($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;
                                case 'textupload':
                                    $field['upload_url'] = $field['upload_url'] ?? $router->buildUrl('/ebcms/admin/upload');
                                    $tmp = (new TextUpload($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;
                                case 'files':
                                    $field['upload_url'] = $field['upload_url'] ?? $router->buildUrl('/ebcms/admin/upload');
                                    $tmp = (new Files($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;
                                case 'radio':
                                    $field['options'] = (function () use ($field): array {
                                        $res = [];
                                        foreach (array_filter(explode('|', $field['options'])) as $value) {
                                            $res[] = [
                                                'label' => $value,
                                                'value' => $value,
                                            ];
                                        }
                                        return $res;
                                    })();
                                    $field['inline'] = $field['inline'] ?? 1;
                                    $tmp = (new Radio($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;
                                case 'checkbox':
                                    $field['options'] = (function () use ($field): array {
                                        $res = [];
                                        foreach (array_filter(explode('|', $field['options'])) as $value) {
                                            $res[] = [
                                                'label' => $value,
                                                'value' => $value,
                                            ];
                                        }
                                        return $res;
                                    })();
                                    $field['value'] = array_filter(explode('|', $field['value']));
                                    $field['inline'] = $field['inline'] ?? 1;
                                    $tmp = (new Checkbox($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? []));
                                    break;
                                case 'select':
                                    $field['options'] = (function () use ($field): array {
                                        $res = [];
                                        foreach (array_filter(explode('|', $field['options'])) as $value) {
                                            $res[] = [
                                                'label' => $value,
                                                'value' => $value,
                                            ];
                                        }
                                        return $res;
                                    })();
                                    $tmp = (new Select($field['label'], 'extra[' . $field['name'] . ']', $extra[$field['name']] ?? ''));
                                    break;

                                default:
                                    throw new LogicException('类型' . $field['type'] . '不支持');
                                    break;
                            }
                            unset($field['type']);
                            unset($field['label']);
                            unset($field['name']);
                            unset($field['value']);
                            foreach ($field as $key => $value) {
                                $tmp->set($key, $value);
                            }
                            $res[] = $tmp;
                        }
                        return $res;
                    })()
                ),
                (new Col('col-md-3'))->addItem(
                    (new Cover('封面图', 'cover', $data['cover'], $router->buildUrl('/ebcms/admin/upload')))
                )
            )
        );
        return $this->html($form->__toString());
    }
    public function post(
        RequestFilter $input,
        Fragment $fragmentModel,
        Content $contentModel
    ) {
        if (!$content = $contentModel->get('*', [
            'id' => $input->post('id'),
        ])) {
            return $this->failure('内容不存在！');
        }

        $update = array_intersect_key($input->post(), [
            'title' => '',
            'redirect_uri' => '',
            'description' => '',
            'cover' => '',
        ]);

        $update['extra'] = serialize($input->post('extra'));

        $contentModel->update($update, [
            'id' => $input->post('id', 0, ['intval']),
        ]);

        $fragmentModel->deleteFragmentCache($content['fragment_id']);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
