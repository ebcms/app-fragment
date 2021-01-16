<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container-lg">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="yulan-tab" data-toggle="tab" href="#yulan" role="tab" aria-controls="yulan" aria-selected="true">预览</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="code-tab" data-toggle="tab" href="#code" role="tab" aria-controls="code" aria-selected="false">渲染结果</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="learn-tab" data-toggle="tab" href="#learn" role="tab" aria-controls="learn" aria-selected="false">调用代码</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="readme-tab" data-toggle="tab" href="#readme" role="tab" aria-controls="readme" aria-selected="false">说明</a>
            </li>
        </ul>
        <div class="tab-content py-3" id="myTabContent">
            <div class="tab-pane fade show active" id="yulan" role="tabpanel" aria-labelledby="yulan-tab">{$result}</div>
            <div class="tab-pane fade" id="code" role="tabpanel" aria-labelledby="code-tab">
                <pre><code>{:htmlspecialchars($result)}</code></pre>
            </div>
            <div class="tab-pane fade" id="learn" role="tabpanel" aria-labelledby="learn-tab">
                <pre><code>{literal}{if function_exists('tpl_fragment')}{fragment '{/literal}{$fragment.id}{literal}', '暂无'}{/if}{/literal}</code></pre>
            </div>
            <div class="tab-pane fade" id="readme" role="tabpanel" aria-labelledby="readme-tab">由于前端样式的不一样，预览可能存在差异，可以自定义预览模板以适应需要。</div>
        </div>
    </div>
    <script src="https://cdn.bootcdn.net/ajax/libs/highlight.js/10.1.1/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@10.1.2/styles/vs.css">
    <script>
        hljs.initHighlightingOnLoad();
    </script>
</body>

</html>