{if $post}
    {block name="post" assign="content"}
        <dl class="details">
            <div class="published row">
                <dt>Published:</dt>
                <dd>{include file="components/date.tpl" class="post-short-published" date=$post->created_at date_format="%b %e, %Y %H:%M:%S"}</dd>
            </div>
            {if $post->updated_at !== $post->created_at}
                <div class="updated row">
                    <dt>Updated:</dt>
                    <dd>{include file="components/date.tpl" class="post-short-published" date=$post->updated_at date_format="%b %e, %Y %H:%M:%S"}</dd>
                </div>
            {/if}
            <div class="categories row">
                <dt>Categories:</dt>
                <dd>{$categories|join:", "}</dd>
            </div>
            <div class="keywords row">
                <dt>Keywords:</dt>
                <dd>{$keywords|join:", "}</dd>
            </div>
            <div class="keywords views">
                <dt>Views:</dt>
                <dd>{$post->views}</dd>
            </div>
        </dl>
        {if $post->hasDescription()}
            <div class="description">
                {$post->description}
            </div>
        {/if}
        <div class="post-content">
            {$post->content->getHtml()}
        </div>
    {/block}
    {include
    file="components/section.tpl"
    class="post"
    level=2
    image=$post->image
    content=$content
    title=$post->name
    }
{/if}