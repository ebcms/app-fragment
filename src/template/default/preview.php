{include common/header@ebcms/admin}
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="yulan-tab" data-bs-toggle="tab" href="#yulan" role="tab" aria-controls="yulan" aria-selected="true">预览</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="code-tab" data-bs-toggle="tab" href="#code" role="tab" aria-controls="code" aria-selected="false">渲染结果</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="learn-tab" data-bs-toggle="tab" href="#learn" role="tab" aria-controls="learn" aria-selected="false">调用代码</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="readme-tab" data-bs-toggle="tab" href="#readme" role="tab" aria-controls="readme" aria-selected="false">说明</a>
    </li>
</ul>
<div class="tab-content py-3">
    <div class="tab-pane fade show active" id="yulan" role="tabpanel" aria-labelledby="yulan-tab">{echo $result}</div>
    <div class="tab-pane fade" id="code" role="tabpanel" aria-labelledby="code-tab">
        <pre><code>{$result}</code></pre>
    </div>
    <div class="tab-pane fade" id="learn" role="tabpanel" aria-labelledby="learn-tab">
        <pre><code>{literal}{if function_exists('tpl_fragment')}{fragment '{/literal}{$fragment.id}{literal}', '暂无'}{/if}{/literal}</code></pre>
    </div>
    <div class="tab-pane fade" id="readme" role="tabpanel" aria-labelledby="readme-tab">由于前端样式的不一样，预览可能存在差异，可以自定义预览模板以适应需要。</div>
</div>
</div>
<script src="https://cdn.bootcdn.net/ajax/libs/highlight.js/11.0.1/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/highlight.js/11.0.1/styles/vs.min.css">
<script>
    hljs.initHighlightingOnLoad(); //highlightAll initHighlightingOnLoad
</script>
{include common/footer@ebcms/admin}