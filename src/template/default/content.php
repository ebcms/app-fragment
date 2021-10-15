{include common/header@ebcms/admin}
<script>
    function priority(index, type) {
        $.ajax({
            type: "POST",
            url: "{:$router->buildUrl('/ebcms/fragment/content/priority')}",
            data: {
                package_name: "{$request->get('package_name')}",
                name: "{$request->get('name')}",
                index: index,
                type: type,
            },
            dataType: "JSON",
            success: function(response) {
                if (!response.code) {
                    alert(response.message);
                } else {
                    location.reload();
                }
            }
        });
    }
</script>
<div class="container-xxl">
    <div class="my-3">
        <a href="{:$router->buildUrl('/ebcms/fragment/content/create', ['package_name'=>$request->get('package_name'),'name'=>$request->get('name')])}" class="btn btn-primary">添加</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="tablexx">
            <tbody>
                {foreach $fragment['contents']??[] as $index => $content}
                <tr>
                    <td>
                        <div>
                            {foreach $content as $key=>$value}
                            <div class="text-muted"><span class="text-danger">[{$key}]</span> {:is_string($value)?$value:json_encode($value)}</div>
                            {/foreach}
                        </div>
                        <div>
                            <a href="#" onclick="priority('{$index}', 'up')">上移</a>
                            <a href="#" onclick="priority('{$index}', 'down')">下移</a>
                            <a href="{:$router->buildUrl('/ebcms/fragment/content/update', ['package_name'=>$request->get('package_name'),'name'=>$request->get('name'),'index'=>$index])}">编辑</a>
                            <a href="{:$router->buildUrl('/ebcms/fragment/content/create', ['package_name'=>$request->get('package_name'),'name'=>$request->get('name'),'index'=>$index])}">复制</a>
                            <a href="{:$router->buildUrl('/ebcms/fragment/content/delete', ['package_name'=>$request->get('package_name'),'name'=>$request->get('name'),'index'=>$index])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        </div>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{include common/footer@ebcms/admin}