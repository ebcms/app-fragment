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
{foreach $fragments as $group => $items}
<details open>
    <summary>{$group}</summary>
    <div class="table-responsive mt-3">
        <table class="table table-bordered">
            <tbody>
                {foreach $items as $vo}
                <tr>
                    <td>
                        <svg t="1611662090285" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="34256" width="20" height="20">
                            <path d="M419.8 379.42l-1.15 1.98c1.49-0.52 2.94-1.18 4.36-1.98h-3.21zM484 450.51h14.75l-18.03-31.23-11.34 19.64A21.43 21.43 0 0 1 484 450.51zM467.09 442.9l-4.39 7.61h16.34c-2.64-4.05-6.95-6.92-11.95-7.61z" fill="#FFFFFF" p-id="34257"></path>
                            <path d="M119.75 107.4h775.84v169.14H119.75z" fill="#E7F3FF" p-id="34258"></path>
                            <path d="M370.43 377.83c-9.37-7.6-23.17-6.21-30.77 3.16l-143.1 175.24 143 174.85c4.34 5.33 10.65 7.99 16.97 7.99 4.83 0 9.76-1.58 13.8-4.93 9.37-7.7 10.75-21.4 3.06-30.77L253.07 556.14 373.49 408.5c7.69-9.27 6.31-23.08-3.06-30.67z m280.47 0c-9.37 7.7-10.75 21.4-3.06 30.76l120.42 147.25-120.51 147.63c-7.6 9.37-6.21 23.17 3.15 30.76 4.05 3.36 8.98 4.93 13.81 4.93 6.31 0 12.62-2.75 16.96-8.08l143-175.35-143-174.85c-7.69-9.36-21.5-10.75-30.77-3.05z m-65.58-29.78c-11.44-4.05-23.86 1.97-27.91 13.31L424.97 736.01c-4.05 11.45 1.97 23.87 13.31 27.92 2.37 0.88 4.83 1.28 7.3 1.28 8.98 0 17.46-5.62 20.61-14.6l132.45-374.55c4.04-11.45-1.88-23.97-13.32-28.01z m0 0" fill="#FF8902" p-id="34259"></path>
                            <path d="M133.54 938.18c-38.38 0-69.61-31.23-69.61-69.62V155.44c0-38.38 31.23-69.62 69.61-69.62h756.91c38.39 0 69.62 31.19 69.62 69.52v713.21c0 38.38-31.23 69.62-69.62 69.62H133.54z m-12.04-69.62c0 6.64 5.4 12.04 12.04 12.04h756.91c6.64 0 12.04-5.4 12.04-12.04V283.44H121.5v585.12z m780.99-642.79v-70.42c0-6.64-5.4-12.05-12.04-12.05H133.54c-6.52 0-12.04 5.51-12.04 12.05v70.42h780.99z" fill="#025AFA" p-id="34260"></path>
                            <path d="M890.45 92.72H133.54c-34.61 0-62.72 28.11-62.72 62.72v713.12c0 34.62 28.11 62.72 62.72 62.72h756.91c34.62 0 62.73-28.1 62.73-62.72V155.35c0-34.52-28.11-62.63-62.73-62.63z m-756.91 43.69h756.91c10.46 0 18.94 8.48 18.94 18.94v77.31H114.61v-77.31c0-10.37 8.58-18.94 18.93-18.94z m756.91 751.08H133.54c-10.45 0-18.93-8.48-18.93-18.93V276.55h794.78v592.01c0 10.35-8.48 18.93-18.94 18.93z m0 0" fill="#025AFA" p-id="34261"></path>
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