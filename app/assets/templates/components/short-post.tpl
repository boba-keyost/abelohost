{if $post}
    {assign var="href" value="/post/"|cat:$post->slug}
    {block name="short-post" assign="content"}
        {include file="components/date.tpl" class="short-post-date" date=$post->updated_at}
        <div class="description">
            {$post->description}
        </div>
        {include file="components/link.tpl" class="short-post-link" href=$href content="Continue Reading"}
    {/block}
    {include
    file="components/section.tpl"
    class="short-post"
    level=3
    image=$post->image
    imageHref=$href
    content=$content
    title=$post->name
    }
{/if}