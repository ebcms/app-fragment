<?php

use Ebcms\App;
use Ebcms\Router;

return App::getInstance()->execute(function (
    Router $router
): array {
    $res = [];
    $res[] = [
        'title' => '碎片',
        'url' => $router->buildUrl('/ebcms/fragment/fragment/index'),
        'icon' => '<svg t="1611661738156" class="icon" viewBox="0 0 1102 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="19834" width="20" height="20"><path d="M275.692308 472.615385l238.749538 137.846153v275.692308L275.692308 1024 36.942769 886.153846v-275.692308z" fill="#178FFF" p-id="19835"></path><path d="M551.384615 0l238.749539 137.846154v275.692308L551.384615 551.384615l-238.749538-137.846153v-275.692308z" fill="#71B9FF" p-id="19836"></path><path d="M827.076923 472.615385l238.749539 137.846153v275.692308L827.076923 1024l-238.749538-137.846154v-275.692308z" fill="#178FFF" p-id="19837"></path></svg>',
    ];
    return $res;
});
