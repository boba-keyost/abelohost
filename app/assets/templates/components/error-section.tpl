{if $error}
    {assign var="error" value=$error|prepare_error}
    {assign var="title" value=$title|default:$error->getMessage()}
    {block name="error-content" assign="content"}
        <dl class="details">
            <div class="message row">
                <dt>Message:</dt>
                <dd>{$error->getMessage()} ({$error->getCode()})</dd>
            </div>
            {if $config->debug}
                <div class="file row">
                    <dt>File:</dt>
                    <dd>{$error->getFile()}:{$error->getLine()}</dd>
                </div>
                <div class="trace row">
                    <dt>Trace:</dt>
                    <dd>{include file="components/code.tpl" class="error-trace" content=$error->getTrace()}</dd>
                </div>
            {/if}
        </dl>
    {/block}
    {include file="components/section.tpl" class="error-section" level=4 content=$content title=$title level=$level}
{/if}