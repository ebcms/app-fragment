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
        'icon' => '<svg t="1607311330376" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="79009" width="20" height="20"><path d="M520 943.712a8 8 0 0 1-8-8v-416a8 8 0 0 1 8-8h416a8 8 0 0 1 8 8v416a8 8 0 0 1-8 8h-416z" fill="#80C33C" p-id="79010"></path><path d="M920 535.712v384h-384v-384h384m16-48h-416a32 32 0 0 0-32 32v416a32 32 0 0 0 32 32h416a32 32 0 0 0 32-32v-416a32 32 0 0 0-32-32z" fill="#3A7033" p-id="79011"></path><path d="M88 894.688a8 8 0 0 1-8-8v-416a8 8 0 0 1 8-8h416a8 8 0 0 1 8 8v416a8 8 0 0 1-8 8h-416z" fill="#E4001D" p-id="79012"></path><path d="M488 486.688v384h-384v-384h384m16-48h-416a32 32 0 0 0-32 32v416a32 32 0 0 0 32 32h416a32 32 0 0 0 32-32v-416a32 32 0 0 0-32-32z" fill="#8C031A" p-id="79013"></path><path d="M264.864 512.288a8 8 0 0 1-8-8v-416a8 8 0 0 1 8-8h416a8 8 0 0 1 8 8v416a8 8 0 0 1-8 8h-416z" fill="#2AB2EC" p-id="79014"></path><path d="M664.864 104.288v384h-384v-384h384m16-48h-416a32 32 0 0 0-32 32v416a32 32 0 0 0 32 32h416a32 32 0 0 0 32-32v-416a32 32 0 0 0-32-32z" fill="#0181B8" p-id="79015"></path></svg>',
    ];
    return $res;
});
