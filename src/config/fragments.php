<?php
return [
    'demo_lunbo' => [
        'type' => 'content',
        'title' => '示例：轮播',
        'template' => (function () {
            $template = <<<'str'
<div id="frament_{:md5($fragment['id'])}" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        {foreach $contents as $key=>$vo}
        <li data-target="#frament_{:md5($fragment['id'])}" data-slide-to="{$key}" class="{$key==0?'active':''}"></li>
        {/foreach}
    </ol>
    <div class="carousel-inner bg-dark">
        {foreach $contents as $key=>$vo}
        <div class="carousel-item {$key==0?'active':''}">
            <a href="{$vo.redirect_uri}" target="_blank">
                <img src="{$vo.cover}" class="d-block w-100" alt="{$vo.title}">
                <div class="carousel-caption d-none d-md-block">
                    <h5>{$vo.title}</h5>
                    <p>{$vo.description}</p>
                </div>
            </a>
        </div>
        {/foreach}
    </div>
    <a class="carousel-control-prev" href="#frament_{:md5($fragment['id'])}" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#frament_{:md5($fragment['id'])}" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
str;
            return htmlspecialchars($template);
        })(),
    ],
    'demo_product' => [
        'type' => 'content',
        'title' => '示例：产品滚动',
        'template' => (function () {
            $template = <<<'str'
<div class="card mb-3 bg-light">
<div class="card-header">{$fragment['title']}</div>
<div class="card-body">
    <marquee direction="left" behavior="alternate" scrollamoun="1" scrolldelay="50" onMouseOver="this.stop()" onMouseOut="this.start()">
        <ul class="list-inline my-0" style="word-spacing: nowarp;">
            {foreach $contents as $vo}
            <li class="list-inline-item">
                <a href="{$vo.redirect_uri}" class="text-decoration-none"><img src="{$vo.cover}" class="img-thumbnail" style="width:200px;height:150px;" alt="{$vo.title}">
                <div class="py-2 text-truncate" style="max-width:180px;">{$vo['title']}</div></a>
            </li>
            {/foreach}
        </ul>
    </marquee>
</div>
</div>
str;
            return htmlspecialchars($template);
        })(),
    ],
    'demo_link' => [
        'type' => 'content',
        'title' => '示例：友情链接',
        'template' => (function () {
            $template = <<<'str'
<div class="mb-3 p-3 bg-light">
    <span class="font-weight-bold mr-2">友情链接</span>
    {foreach $contents as $vo}
        <a class="mr-2" href="{$vo.redirect_uri}" target="_blank">{$vo.title}</a>
    {/foreach}
</div>
str;
            return htmlspecialchars($template);
        })(),
    ],
    'demo_gonggao' => [
        'type' => 'content',
        'title' => '示例：公告',
        'template' => (function () {
            $template = <<<'str'
<div class="card bg-light mb-3">
    <div class="card-header">{$fragment['title']}</div>
    <div class="card-body">
        <ul class="list-unstyled mb-0">
            {foreach $contents as $vo}
            <li>
                <a href="{$vo.redirect_uri}" target="_blank">▪ {$vo.title}</a>
            </li>
            {/foreach}
        </ul>
    </div>
</div>
str;
            return htmlspecialchars($template);
        })(),
    ],
    'demo_about' => [
        'type' => 'editor',
        'title' => '示例：关于我们',
        'content' => (function () {
            $content = <<<'str'
<img src="http://118.25.184.158/project/public/uploads/2020/12-15/5fd81649d738f.jpg" style="width: 226px; float: left;" class="note-float-left mr-3"><p>阿里巴巴集团的使命是让天下没有难做的生意。</p><p>我们旨在助力企业，帮助其变革营销、销售和经营的方式，提升其效率。我们为商家、品牌及其他企业提供技术基础设施以及营销平台，帮助其借助新技术的力量与用户和客户进行互动，并更高效地进行经营。</p><p>我们的业务包括核心商业、云计算、数字媒体及娱乐以及创新业务。除此之外， 我们的非并表关联方蚂蚁集团为我们平台上的消费者和商家提供支付服务和金融服务。围绕着我们的平台与业务，一个涵盖了消费者、商家、品牌、零售商、第三方服务提供商、战略合作伙伴及其他企业的数字经济体已经建立。</p><p>截至2020年3月31 日止12个月期间，阿里巴巴数字经济体产生了人民币70,530亿元（10,000亿美元*）的GMV，主要包括通过我们的中国零售市场交易的人民币65,890亿元（9,450亿美元）的GMV，以及通过我们的国际零售市场和本地生活服务交易的GMV。</p>
str;
            return html_entity_decode($content);
        })(),
        'template' => (function () {
            $template = <<<'str'
<div class="card mb-3 bg-light">
    <div class="card-header">{$fragment['title']}</div>
    <div class="card-body">
        {:htmlspecialchars_decode($fragment['content']?:'')}
    </div>
</div>
str;
            return htmlspecialchars($template);
        })(),
    ]
];
