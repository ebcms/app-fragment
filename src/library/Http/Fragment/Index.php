<?php

declare(strict_types=1);

namespace App\Ebcms\Fragment\Http\Fragment;

use App\Ebcms\Admin\Http\Common;
use Ebcms\App;
use Ebcms\Config;
use Ebcms\Template;

class Index extends Common
{

    public function get(
        App $app,
        Config $config,
        Template $template
    ) {
        $fragments = [];
        foreach (array_keys($app->getPackages()) as $package_name) {
            if ($items = $config->get('fragments@' . $package_name, [])) {
                $fragments[$package_name] = $items;
            }
        }

        return $this->html($template->renderFromFile('fragment@ebcms/fragment', [
            'fragments' => $fragments,
        ]));
    }
}
