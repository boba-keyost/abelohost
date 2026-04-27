{assign var="level" value=$level|default:3}
{if $content}
    {include file="components/image.tpl" class="section-image" src=$image href=$imageHref alt=$image_alt|default:$title assign="image_content"}
    {block name="title" assign="title_content"}
        {if $title || $controls}
            <div class="title">
                {if $title}
                    <h{$level} class="text">
                        {if $titleHref}
                            {include file="components/link.tpl" class="title-link" href=$titleHref content=$title}
                        {else}
                            {$title}
                        {/if}
                    </h{$level}>
                {/if}
                {include file="components/controls.tpl" class="title-controls" controls=$controls}
            </div>
        {/if}
    {/block}
    <section class="section{if $class} {$class}{/if}" about="{$about}">
        {if !$image_in_content}{$image_content}{/if}
        {if !$title_in_content}{$title_content}{/if}
        <div class="content">
            {if $image_in_content}{$image_content}{/if}
            {if $title_in_content}{$title_content}{/if}
            {$content}
        </div>
    </section>
{/if}