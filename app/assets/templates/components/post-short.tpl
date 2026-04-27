{if $post}
    {assign var="href" value="/post/"|cat:$post->slug}
    {block name="post-short" assign="content"}
        {include file="components/date.tpl" class="post-short-date" date=$post->updated_at}
        <div class="description">
            {$post->description}
        </div>
        {include file="components/link.tpl" class="post-short-link" href=$href content="Continue Reading"}
    {/block}
    {include
    file="components/section.tpl"
    class="post-short"
    level=3
    image=$post->image
    imageHref=$href
    content=$content
    title=$post->name
    titleHref=$href
    }
{/if}