{include common/header@ebcms/admin}
<script>
    function priority(id, type) {
        $.ajax({
            type: "POST",
            url: "{:$router->buildUrl('/ebcms/fragment/content/priority')}",
            data: {
                id: id,
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
<div class="my-3">
    <a href="{:$router->buildUrl('/ebcms/fragment/content/create', ['fragment_id'=>$request->get('fragment_id')])}" class="btn btn-primary">添加</a>
</div>
<div class="table-responsive">
    <table class="table table-bordered" id="tablexx">
        <thead>
            <tr>
                <th class="text-nowrap">标题</th>
                <th class="text-nowrap">排序</th>
                <th class="text-nowrap">管理</th>
            </tr>
        </thead>
        <tbody>
            {foreach $data as $k => $vo}
            <tr>
                <td class="text-nowrap text-truncate align-middle" style="max-width: 20em;">
                    {if $vo['cover']}
                    <svg t="1602825819135" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="31699" width="20" height="20">
                        <path d="M829.64898 849.502041H194.35102c-43.885714 0-79.412245-35.526531-79.412244-79.412245V253.910204c0-43.885714 35.526531-79.412245 79.412244-79.412245h635.29796c43.885714 0 79.412245 35.526531 79.412244 79.412245v516.179592c0 43.885714-35.526531 79.412245-79.412244 79.412245z" fill="#D2F4FF" p-id="31700"></path>
                        <path d="M909.061224 656.195918l-39.706122-48.065306L626.416327 365.714286c-19.330612-19.330612-50.677551-19.330612-70.008164 0L419.526531 502.073469c-2.612245 2.612245-5.22449 3.134694-6.791837 3.134694-1.567347 0-4.702041-0.522449-6.791837-3.134694L368.326531 464.979592c-19.330612-19.330612-50.677551-19.330612-70.008164 0l-143.673469 143.673469-39.706122 48.065306v113.893878c0 43.885714 35.526531 79.412245 79.412244 79.412245h635.29796c43.885714 0 79.412245-35.526531 79.412244-79.412245v-114.416327" fill="#16C4AF" p-id="31701"></path>
                        <path d="M273.763265 313.469388m-49.632653 0a49.632653 49.632653 0 1 0 99.265306 0 49.632653 49.632653 0 1 0-99.265306 0Z" fill="#E5404F" p-id="31702"></path>
                        <path d="M644.179592 768h-365.714286c-11.493878 0-20.897959-9.404082-20.897959-20.897959s9.404082-20.897959 20.897959-20.897959h365.714286c11.493878 0 20.897959 9.404082 20.897959 20.897959s-9.404082 20.897959-20.897959 20.897959zM461.322449 670.82449h-182.857143c-11.493878 0-20.897959-9.404082-20.897959-20.897959s9.404082-20.897959 20.897959-20.89796h182.857143c11.493878 0 20.897959 9.404082 20.897959 20.89796s-9.404082 20.897959-20.897959 20.897959z" fill="#0B9682" p-id="31703"></path>
                    </svg>
                    {/if}
                    <span>{$vo.title}</span>
                </td>
                <td class="text-nowrap">
                    <a href="#" onclick="priority('{$vo.id}', 'up')">上移</a>
                    <a href="#" onclick="priority('{$vo.id}', 'down')">下移</a>
                </td>
                <td class="text-nowrap">
                    <a href="{:$router->buildUrl('/ebcms/fragment/content/update', ['id'=>$vo['id']])}">编辑</a>
                    <a href="{:$router->buildUrl('/ebcms/fragment/content/create', ['fragment_id'=>$vo['fragment_id'], 'copyfrom'=>$vo['id']])}">复制</a>
                    <a href="{:$router->buildUrl('/ebcms/fragment/content/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
{include common/footer@ebcms/admin}