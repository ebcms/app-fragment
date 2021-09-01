{include common/header@ebcms/admin}
<div class="my-4 h1">碎片</div>
<div class="my-3">
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            新建
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{:$router->buildUrl('/ebcms/fragment/fragment/create', ['type'=>'template'])}">模板碎片</a>
            <a class="dropdown-item" href="{:$router->buildUrl('/ebcms/fragment/fragment/create', ['type'=>'editor'])}">富文本碎片</a>
            <a class="dropdown-item" href="{:$router->buildUrl('/ebcms/fragment/fragment/create', ['type'=>'content'])}">多内容碎片</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/holderjs@2.9.6/holder.min.js"></script>
{foreach $fragments as $pkg_name => $items}
<div class="fs-5">{$pkg_name}</div>
{foreach $items as $name=>$vo}
<div class="border p-2 mr-3 mb-3 d-inline-block">
    <div class="mb-2">
        <img src="{$vo['cover']??''}" data-src="holder.js/300x200?auto=yes&text=nopic&size=25" class="img-fluid rounded" style="max-width:150px;max-height:150px;" alt="...">
    </div>
    <div class="text-muted">{$name}</div>
    <div>
        <a href="{:$router->buildUrl('/ebcms/fragment/fragment/update', ['package_name'=>$pkg_name,'name'=>$name])}">编辑</a>
        {if $pkg_name=='ebcms/fragment'}
        <a href="{:$router->buildUrl('/ebcms/fragment/fragment/delete', ['package_name'=>$pkg_name,'name'=>$name])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
        {else}
        <a href="{:$router->buildUrl('/ebcms/fragment/fragment/delete', ['package_name'=>$pkg_name,'name'=>$name])}" onclick="return confirm('确定重置吗？');">重置</a>
        {/if}
        {if $vo['type'] == 'content'}
        <a href="javascript:M.open({url:'{:$router->buildUrl('/ebcms/fragment/content/index', ['package_name'=>$pkg_name,'name'=>$name])}', title:'内容 {$name}'});">内容({:count($vo['contents']??[])})</a>
        {/if}
    </div>
</div>
{/foreach}
{/foreach}
{include common/footer@ebcms/admin}