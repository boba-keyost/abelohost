{if $controls}
    <ul class="controls{if $class} {$class}{/if}">
        {foreach $controls as $cnt}
            <li class="item">{$cnt}</li>
        {/foreach}
    </ul>
{/if}