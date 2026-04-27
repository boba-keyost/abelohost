{if $category}
    {assign var="post_ids" value=$category->posts_ids|default:$post_ids}
    {block name="category" assign="content"}
        {if $post_ids}
            {foreach $post_ids as $pid}
                {include file="components/post-short.tpl" class="item" post=$posts->getByKey($pid)}
            {/foreach}
        {else}
            {foreach $posts as $post}
                {include file="components/post-short.tpl" class="item" post=$post}
            {/foreach}
        {/if}
    {/block}
    {if $add_view_all}
        {include file="components/link.tpl" class="short-category-link" href="category/"|cat:$category->slug assign="view_category" content="View all"}
        {append var="controls" value=$view_category}
    {/if}
    {include
    file="components/section.tpl"
    class="short-category"
    level=2
    content=$content
    controls=$controls
    title=$category->name
    }
{/if}