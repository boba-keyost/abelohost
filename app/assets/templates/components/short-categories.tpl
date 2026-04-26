<div class="short-categories{if $class} {$class}{/if}">
    {if $categories && $posts}
        {foreach $categories as $category}
            {include file="components/short-category.tpl" class="item" category=$category posts=$posts add_view_all=true}
        {/foreach}
    {else}
        {include file="components/no-data.tpl" class="no-categories" content="No categories"}
    {/if}
</div>