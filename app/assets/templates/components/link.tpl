{if $href}
    <a class="link{if $class} {$class}{/if}"
       href="{$href|escape}"{if $target} target="{$target === true ? "_blank" : $target}"{/if}>{$content}</a>
{/if}