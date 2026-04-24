{include file="components/header.tpl"}

{if $error}
    <section about="error">
    <pre>{$error|@var_dump}</pre>
    </section>{/if}
<pre>
{$data|@var_dump}
</pre>

{include file="components/footer.tpl"}