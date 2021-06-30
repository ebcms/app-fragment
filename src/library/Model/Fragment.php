<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Model;

use Ebcms\App;
use Ebcms\Config;
use Ebcms\Container;
use Psr\SimpleCache\CacheInterface;
use Throwable;
use Ebcms\Template;

class Fragment
{
    public function all(): array
    {
        static $datas;
        if (!is_array($datas)) {
            $datas = [];
            foreach (array_keys(App::getInstance()->getPackages()) as $package_name) {
                $items = $this->getConfig()->get('fragments@' . $package_name);
                if (is_array($items)) {
                    foreach ($items as $name => $item) {
                        $id = $this->getId($package_name, $name);
                        $item = array_merge([
                            'type' => '',
                            'title' => '',
                            'ttl' => '',
                            'template' => '',
                            'content' => '',
                            'fields' => '',
                            'preview_template' => '',
                        ], (array)$item, [
                            'id' => $id,
                            'package_name' => $package_name,
                            'name' => $name,
                        ]);
                        if (
                            $item['type'] &&
                            $item['title']
                        ) {
                            $datas[$id] = $item;
                        }
                    }
                }
            }
        }
        return $datas;
    }

    public function get(string $id)
    {
        return $this->all()[$id] ?? null;
    }

    public function getId(string $package_name, string $name): string
    {
        return str_replace('/', '.', $package_name) . '.' . $name;
    }

    public function render(string $id, string $default = ''): string
    {
        try {
            $cache = $this->getCache();
            if ($cache && $cache->has('ebcms_fragment_' . $id)) {
                return $cache->get('ebcms_fragment_' . $id);
            }
            if (!$fragment = $this->get($id)) {
                return $default;
            }
            $res = $this->renderByFragment($fragment);
            if ($cache) {
                $cache->set('ebcms_fragment_' . $id, $res, $fragment['ttl'] ?: null);
            }
            return $res;
        } catch (Throwable $th) {
            ob_clean();
            return 'fragment "' . $id . '" render error!' . $th->getMessage();
        }
    }

    public function renderByFragment(array $fragment): string
    {
        switch ($fragment['type']) {
            case 'template':
                return $this->getTemplate()->renderFromString($fragment['template'], [
                    'fragment' => $fragment
                ]);
                break;
            case 'editor':
                return $this->getTemplate()->renderFromString(strlen($fragment['template']) ? $fragment['template'] : '{echo $fragment[\'content\'] ?? \'\'}', [
                    'fragment' => $fragment
                ]);
                break;
            case 'content':
                $contentModel = (function (): Content {
                    return App::getInstance()->execute(function (Content $content): Content {
                        return $content;
                    });
                })();
                return $this->getTemplate()->renderFromString($fragment['template'], [
                    'fragment' => $fragment,
                    'contents' => $contentModel->select('*', [
                        'fragment_id' => $fragment['id'],
                        'ORDER' => [
                            'priority' => 'DESC',
                            'id' => 'ASC',
                        ],
                    ]),
                ]);
                break;
        }
        return '';
    }

    public function deleteFragmentCache(string $id): bool
    {
        if ($cache = $this->getCache()) {
            return $cache->delete('ebcms_fragment_' . $id);
        }
        return true;
    }

    private function getCache(): ?CacheInterface
    {
        return App::getInstance()->execute(function (Container $container): ?CacheInterface {
            if ($container->has(CacheInterface::class)) {
                return $container->get(CacheInterface::class);
            }
            return null;
        });
    }

    private function getTemplate(): Template
    {
        return App::getInstance()->execute(function (Template $template): Template {
            return $template;
        });
    }
    private function getConfig(): Config
    {
        return App::getInstance()->execute(function (Config $config): Config {
            return $config;
        });
    }
}
