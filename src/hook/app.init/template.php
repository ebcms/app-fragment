<?php

use Ebcms\App;
use Ebcms\Template;

App::getInstance()->getContainer()->callback(Template::class, function (Template $template) {

    $template->extend('/\{fragment\s*(.*)\s*[\/]*\}/Ui', function ($matchs) {
        $str = <<<'str'
<?php
echo \Ebcms\App::getInstance()->execute(function (
    \Ebcms\Config $config,
    \Psr\SimpleCache\CacheInterface $cache
): string {
str;
        $str .= 'list($id, $default)=[' . $matchs[1] . '];';
        $str .= <<<'str'
    if ($cache->has(md5('fragment_' . $id))) {
        return $cache->get(md5('fragment_' . $id));
    } else {
        list($name, $package_name) = explode('@', $id);
        $package_name = str_replace('.', '/', $package_name);
        $fragments = $config->get('fragments@' . $package_name, []);
        if (!isset($fragments[$name])) {
            return $default;
        }
        $fragment = $fragments[$name];
        switch ($fragment['type']) {
            case 'template':
                $res = \Ebcms\App::getInstance()->getContainer()->get(\Ebcms\Template::class, true)->renderFromString($fragment['template'], array_merge(['contents'=>[],'content'=>''], $fragment), $id . '(fragment)');
                break;
            case 'editor':
                $res = $fragment['content'];
                break;
            case 'content':
                $res = \Ebcms\App::getInstance()->getContainer()->get(\Ebcms\Template::class, true)->renderFromString($fragment['template'], array_merge(['contents'=>[],'content'=>''], $fragment), $id . '(fragment)');
                break;
            default:
                return $default;
                break;
        }
        $cache->set(md5('fragment_' . $id), $res, intval($fragment['ttl'] ?? 3600));
        return $res;
    }
});
?>
str;
        return $str;
    });
});
