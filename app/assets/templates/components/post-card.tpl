{if $post}
    {assign var="href" value="/post/"|cat:$post->slug}
    {block name="post-short" assign="content"}
        {include file="components/date.tpl" class="post-card-date" date=$post->updated_at}
        <div class="description">
            {$post->description|cut_text:150}
        </div>
        {include file="components/link.tpl" class="post-card-link" href=$href content="Continue Reading"}
    {/block}
    {include
    file="components/section.tpl"
    class="post-card"
    level=5
    image=$post->image
    imageHref=$href
    content=$content
    title=$post->name
    titleHref=$href
    }
{/if}