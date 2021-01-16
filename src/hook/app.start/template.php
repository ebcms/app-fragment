<?php

use App\Ebcms\Fragment\Model\Fragment;
use Ebcms\App;
use Ebcms\Template;

App::getInstance()->execute(function (
    Template $template
) {
    $template->extend('/\{fragment\s*(.*)\s*[\/]*\}/Ui', function ($matchs) {
        return '<?php echo tpl_fragment(' . $matchs[1] . ');?>';
    });
});

if (!function_exists('tpl_fragment')) {
    function tpl_fragment(string $id, string $default = ''): string
    {
        return App::getInstance()->execute(function (
            Fragment $fragment
        ) use ($id, $default): string {
            return $fragment->render($id, $default);
        });
    }
}
