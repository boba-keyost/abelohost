{if $src}
    {block name="image" assign="content"}
        <img src="{$src}" width="100%" alt="{$alt}"/>
    {/block}
    <div class="image{if $class} {$class}{/if}">
        {if $href}
            {include file="components/link.tpl" class="image-link" href=$href content=$content}
        {else}
            {$content}
        {/if}
    </div>
{/if}