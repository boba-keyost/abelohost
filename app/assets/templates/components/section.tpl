{assign var="level" value=$level|default:3}
{if $content}
    <section class="section{if $class} {$class}{/if}" about="{$about}">
        {include file="components/image.tpl" class="section-image" src=$image href=$imageHref alt=$image_alt|default:$title}

        {if $title || $controls}
            <div class="title">
                {if $title}
                    <h{$level} class="text">{$title}</h{$level}>
                {/if}
                {include file="components/controls.tpl" class="title-controls" controls=$controls}
            </div>
        {/if}

        <div class="content">
            {$content}
        </div>
    </section>
{/if}