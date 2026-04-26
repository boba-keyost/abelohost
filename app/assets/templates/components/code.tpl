{if $content}
    <pre class="code{if $class} {$class}{/if}">{$content|code_to_string:$modifier}</pre>{/if}