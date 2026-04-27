{include file="components/header.tpl"}

{include file="components/code-section.tpl" content=$error title="Error"}

{include file="components/post-full.tpl" post=$post}

<aside class="sidebar">
    {if count($similar_posts)}
        {block name="similar_posts" assign="similar_posts_content"}
            <ul class="similar-posts-list">
                {foreach $similar_posts as $post}
                    <li class="item">
                        {include file="components/post-card.tpl" class="similar-posts-list" content=$similar_posts_content title="Similar posts" title_in_content=true image_with_title=false}
                    </li>
                {/foreach}
            </ul>
        {/block}
        {include file="components/section.tpl" class="similar-posts" content=$similar_posts_content title="Similar posts"}
    {/if}
</aside>

{include file="components/footer.tpl"}