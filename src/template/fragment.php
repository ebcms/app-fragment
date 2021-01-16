{include common/header@ebcms/admin}
<div class="my-4 display-4">碎片</div>
<hr>
<div class="my-3">
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            新建
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{:$router->buildUrl('/ebcms/fragment/fragment/create', ['type'=>'template'])}">模板碎片</a>
            <a class="dropdown-item" href="{:$router->buildUrl('/ebcms/fragment/fragment/create', ['type'=>'editor'])}">富文本碎片</a>
            <a class="dropdown-item" href="{:$router->buildUrl('/ebcms/fragment/fragment/create', ['type'=>'content'])}">多内容碎片</a>
        </div>
    </div>
</div>
{foreach $fragments as $group => $items}
<details open>
    <summary>{$group}</summary>
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <tbody>
                {foreach $items as $vo}
                <tr>
                    <td>
                        <svg t="1607311330376" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="79009" width="20" height="20">
                            <path d="M520 943.712a8 8 0 0 1-8-8v-416a8 8 0 0 1 8-8h416a8 8 0 0 1 8 8v416a8 8 0 0 1-8 8h-416z" fill="#80C33C" p-id="79010"></path>
                            <path d="M920 535.712v384h-384v-384h384m16-48h-416a32 32 0 0 0-32 32v416a32 32 0 0 0 32 32h416a32 32 0 0 0 32-32v-416a32 32 0 0 0-32-32z" fill="#3A7033" p-id="79011"></path>
                            <path d="M88 894.688a8 8 0 0 1-8-8v-416a8 8 0 0 1 8-8h416a8 8 0 0 1 8 8v416a8 8 0 0 1-8 8h-416z" fill="#E4001D" p-id="79012"></path>
                            <path d="M488 486.688v384h-384v-384h384m16-48h-416a32 32 0 0 0-32 32v416a32 32 0 0 0 32 32h416a32 32 0 0 0 32-32v-416a32 32 0 0 0-32-32z" fill="#8C031A" p-id="79013"></path>
                            <path d="M264.864 512.288a8 8 0 0 1-8-8v-416a8 8 0 0 1 8-8h416a8 8 0 0 1 8 8v416a8 8 0 0 1-8 8h-416z" fill="#2AB2EC" p-id="79014"></path>
                            <path d="M664.864 104.288v384h-384v-384h384m16-48h-416a32 32 0 0 0-32 32v416a32 32 0 0 0 32 32h416a32 32 0 0 0 32-32v-416a32 32 0 0 0-32-32z" fill="#0181B8" p-id="79015"></path>
                        </svg>
                        {$vo.title}
                    </td>
                    <td class="text-nowrap">
                        <a href="{:$router->buildUrl('/ebcms/fragment/fragment/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="javascript:M.open({url:'{:$router->buildUrl('/ebcms/fragment/fragment/preview', ['id'=>$vo['id']])}', title:'预览 {$vo.title}',size:'lg'});">预览</a>
                        {if $group=='ebcms/fragment'}
                        <a href="{:$router->buildUrl('/ebcms/fragment/fragment/delete', ['package_name'=>$vo['package_name'],'name'=>$vo['name']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        {else}
                        <a href="{:$router->buildUrl('/ebcms/fragment/fragment/delete', ['package_name'=>$vo['package_name'],'name'=>$vo['name']])}" onclick="return confirm('确定重置吗？');">重置</a>
                        {/if}
                        {if $vo['type'] == 'content'}
                        <a href="javascript:M.open({url:'{:$router->buildUrl('/ebcms/fragment/content/index', ['fragment_id'=>$vo['id']])}', title:'内容 {$vo.title}'});">内容({$vo['content_count']??0})</a>
                        {/if}
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</details>
{/foreach}
{include common/footer@ebcms/admin}